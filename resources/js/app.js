// นำเข้า Vue ก่อน
import Vue from 'vue';

// นำเข้า BootstrapVue และ Vuetify
import BootstrapVue from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.min.css'; // CSS ของ Bootstrap
import 'bootstrap-vue/dist/bootstrap-vue.css'; // CSS ของ BootstrapVue



// ใช้งาน Vuetify และ BootstrapVue
Vue.use(BootstrapVue); // ใช้ BootstrapVue


// นำเข้าคอมโพเนนต์ของคุณ
import App from './components/App.vue';
import Product from './components/Product.vue';
import Navbar from './components/Navbar.vue';
import ProductCrud from './components/ProductCrud.vue';
import Customer from './components/Customer.vue';
import SaleProduct from './components/SaleProduct.vue';
import SearchInvoice from './components/SearchInvoice.vue';

// ใช้ FontAwesome
import 'font-awesome/css/font-awesome.min.css';

// ลงทะเบียนคอมโพเนนต์
Vue.component('app', App);
Vue.component('Product', Product);
Vue.component('Navbar', Navbar);
Vue.component('Customer', Customer);
Vue.component('SaleProduct', SaleProduct);
Vue.component('SearchInvoice', SearchInvoice);

// สร้าง Vue instance
const app = new Vue({
    el: '#app',
    components: { ProductCrud },  // คอมโพเนนต์ที่ต้องการใช้งาน

});
