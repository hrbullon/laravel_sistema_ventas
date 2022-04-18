<script>
export default {
    props: ['title']
}
</script>
<template>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Inicio</li>
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active">{{ title }}</li>
    </ol>
</template>