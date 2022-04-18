<script setup>

import Swal from 'sweetalert2';

import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import Breadcrumb from '@/Layouts/shared/Breadcrumb.vue';

import { CategoryService } from '@/Services/CategoryService';

</script>

<template>
    <BreezeAuthenticatedLayout>
        <Breadcrumb :title="title"/>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> {{ title }}
                    <button type="button" @click="openModal('add')" class="btn btn-secondary">
                        <i class="icon-plus"></i> Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="pagination.per_page">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                </select>
                                <select class="form-control col-md-3" v-model="criteria">
                                    <option value="name">Nombre</option>
                                    <option value="description">Descripción</option>
                                </select>
                                <input type="text" v-model="filter" @keyup.enter="getCategories(1, filter, criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" @click="getCategories(1, filter, criteria)" ><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th v-for="column in columns" :key="column.text" v-text="column.text"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="categories.length == 0">
                                <td colspan="4" class="text-center">Cargando...</td>
                            </tr>
                            <tr v-for="category in categories" :key="category.id">
                                <td>
                                    <button type="button" @click="openModal('update',category)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <button type="button"  @click="changeCondition(category)" class="btn btn-sm" :class="{ 'btn-danger' : category.condition, 'btn-info' : !category.condition }">
                                        <i v-if="category.condition" class="icon-trash"></i>
                                        <i v-else class="icon-check"></i>
                                    </button>
                                </td>
                                <td v-text="category.name"></td>
                                <td v-text="category.description"></td>
                                <td>
                                    <div v-if="category.condition">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="this.getCategories(pagination.current_page - 1, filter, criteria)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in totalRow" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="this.getCategories(page, filter, criteria)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="this.getCategories(pagination.current_page + 1, filter, criteria)">Sig</a>
                            </li>
                        </ul>
                    </nav>  
                </div>
            </div>
        </div>
        <!--Start Modal add/update-->
        <div class="modal fade" tabindex="-1" :class="{'show' : modal}" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="modalTitle"></h4>
                        <button type="button" class="close" @click="closeModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form enctype="multipart/form-data" class="form-horizontal">
                            <div v-show="!validate" class="alert alert-danger text-error" role="alert">
                                El nombre de la categoría no puede estar vacío.
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="model.name" class="form-control" placeholder="Nombre de categoría" autocomplete="off">
                                    <span class="help-block">(*) Ingrese el nombre de la categoría</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="model.description" class="form-control" placeholder="Descripción de categoría" autocomplete="off">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button v-if="action==1" type="button" class="btn btn-primary" @click="addCategory()">Guardar</button>
                        <button v-if="action==2" type="button" class="btn btn-success" @click="updateCategory()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--End Modal add/update-->
    </BreezeAuthenticatedLayout>
</template>

<script>

export default {
    data() {
        return {
            title : "Categorias",
            category_id: 0,
            model: {
                name: "",
                description:""
            },
            categories: [],
            validate: true,
            modal: false,
            modalTitle: "",
            action: 0,
            criteria: "name",
            filter: "",
            pagination: {
                current_page:0,
                from: 0,
                per_page: 5,
                last_page: 0,
                to: 0,
                total: 0,
                links: []
            },
            columns: [
                {
                    text: 'Opciones'
                },
                {
                    text: 'Nombre'
                },
                {
                    text: 'Descripción'
                },
                {
                    text: 'Estado'
                }
            ]
        }
    },
    computed: {
        isActived(){
            return this.pagination.current_page;
        },
        totalRow(){
            return Math.round(this.pagination.total/this.pagination.per_page);
        }
    },
    methods: {
        getCategories(page = 1, filter ='', criteria = ''){
            CategoryService.getCategories(page,this.pagination.per_page,filter,criteria).then( response => {
                this.categories = response.data;
                this.pagination.total = response.total;
                this.pagination.per_page = response.per_page;
                this.pagination.current_page = response.current_page;
                this.pagination.last_page = response.last_page;
            });
        },
        addCategory(){
            if(!this.validateForm()){
                return;
            }
            CategoryService.addCategory(this.model)
            .then((category) => this.finnishAction());

        },
        updateCategory() {
            if(!this.validateForm()){
                return;
            }
            CategoryService.updateCategory(this.model, this.category_id)
            .then((category) => this.finnishAction());
        },
        getCondition(category){
            return '<button>Test</button>'
        },
        changeCondition(category){

            let action = (!category.condition)? 'activate' : 'deactivate';

            Swal.fire({
                title: (category.condition)? 'Quiere desactivar ?' : 'Quiere activar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: (category.condition)? 'Sí, desactivar!' : 'Sí, activar!'
                }).then((result) => {

                if (result.isConfirmed) {
                    CategoryService.changeCondition(action, category.id)
                    .then((category) => this.finnishAction());
                }
            })
        },
        validateForm(){
            this.validate = (!this.model.name)? false :  true;
            return this.validate;
        },
        closeModal(){
            this.modal=0;
            this.modalTitle='';
            this.model.name='';
            this.model.description='';
        },
        finnishAction(){
            this.closeModal();
            this.getCategories();

            Swal.fire('Listo','Operación realizada.','success');
        },
        openModal(action, data = []){
            switch (action) {
                case "add":
                        this.modal = true;
                        this.modalTitle = "Registrar Categoría";
                        this.model.name = "";
                        this.model.description = "";
                        this.action = 1
                    break;
                case "update":
                        this.modal = true;
                        this.modalTitle = "Actualizar Categoría";
                        this.category_id = data['id']; 
                        this.model.name = data['name'];
                        this.model.description = data['description'];
                        this.action = 2
                    break;
                default:
                    break;
            }
        }
    },
    mounted() {
        this.getCategories();
    }
}
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .show{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>