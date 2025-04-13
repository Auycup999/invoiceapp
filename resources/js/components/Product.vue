<template>
    <div>
    
        <h2>รายการสินค้า</h2>
    
    
    
        <!-- ตารางแสดงข้อมูลสินค้า -->
    
        <table class="table">
    
            <thead>
    
                <tr>
    
                    <th>รหัสสินค้า</th>
    
                    <th>ชื่อสินค้า</th>
    
                    <th>ราคา</th>
    
                    <th>หน่วย</th>
    
                    <th>การจัดการ</th>
    
                </tr>
    
            </thead>
    
            <tbody>
    
                <tr v-for="product in products" :key="product.id">
    
                    <td>{{ product.barcode }}</td>
    
                    <td>{{ product.name }}</td>
    
                    <td>{{ product.price }}</td>
    
                    <td>{{ product.unit }}</td>
    
                    <td>
    
                        <button @click="editProduct(product.id)" class="btn btn-warning">แก้ไข</button>
    
                        <button @click="deleteProduct(product.id)" class="btn btn-danger">ลบ</button>
    
                    </td>
    
                </tr>
    
            </tbody>
    
        </table>
    
    
    
        <!-- ฟอร์มสำหรับเพิ่มข้อมูลสินค้า -->
    
        <div v-if="showForm">
    
            <h4>เพิ่มข้อมูลสินค้า</h4>
    
            <form @submit.prevent="addProduct">
    
                <div>
    
                    <label>รหัสสินค้า:</label>
    
                    <input v-model="newProduct.barcode" type="text" required>
    
                </div>
    
                <div>
    
                    <label>ชื่อสินค้า:</label>
    
                    <input v-model="newProduct.name" type="text" required>
    
                </div>
    
                <div>
    
                    <label>ราคา:</label>
    
                    <input v-model="newProduct.price" type="number" required>
    
                </div>
    
                <div>
    
                    <label>หน่วย:</label>
    
                    <input v-model="newProduct.unit" type="text" required>
    
                </div>
    
                <button type="submit" class="btn btn-primary">เพิ่มสินค้า</button>
    
            </form>
    
        </div>
    
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            products: [],
            newProduct: {
                barcode: '',
                name: '',
                price: 0,
                unit: ''
            },
            showForm: false
        };
    },
    methods: {
        fetchProducts() {
            axios.get('/api/products')
                .then(response => {
                    this.products = response.data;
                });
        },
        addProduct() {
            axios.post('/api/products', this.newProduct)
                .then(() => {
                    this.fetchProducts();
                    this.newProduct = { barcode: '', name: '', price: 0, unit: '' };
                    this.showForm = false;
                });
        },
        deleteProduct(id) {
            axios.delete(`/api/products/${id}`)
                .then(() => {
                    this.fetchProducts();
                });
        },
        editProduct(id) {
            // เพิ่มฟังก์ชันการแก้ไขข้อมูล (ตัวอย่างนี้ไม่แสดง)
        }
    },
    mounted() {
        this.fetchProducts();
    }
};
</script>

<style scoped>
.table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 8px;
    border: 1px solid #ddd;
    font-size: 36px;
}

input[type="text"],
input[type="number"] {
    font-size: 16px;
    padding: 5px;
}

button {
    margin-right: 10px;
}
</style>
