

export const CategoryService = {
    async getCategories(page = 1, per_page = 5,filter = '',criteria = '') {
        return await axios.get(`/api/categories?page=${page}&per_page=${per_page}&criteria=${criteria}&filter=${filter}`).then( (response) => response.data );
    },
    async addCategory(category) {
        return await axios.post('/api/categories', category);
    },
    async updateCategory(category, id) {
        return await axios.put(`/api/categories/${id}`, category);
    },
    async changeCondition(action, id) {
        return await axios.put(`/api/categories/${action}/${id}`);
    }
};
export default { CategoryService };