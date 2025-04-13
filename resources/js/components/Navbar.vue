<template>
  <div>
    <!-- เมนูบาร์ -->
    <nav class="navbar navbar-expand-lg" style="background-color: #007bff;">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item" @click="selectTab('home')">
              <a class="nav-link text-white" href="#">เริ่มต้น</a>
            </li>
            <li class="nav-item" @click="selectTab('products')">
              <a class="nav-link text-white" href="#">สินค้า</a>
            </li>
            <li class="nav-item" @click="selectTab('customers')">
              <a class="nav-link text-white" href="#">ลูกค้า</a>
            </li>
            <li class="nav-item" @click="selectTab('tools')">
              <a class="nav-link text-white" href="#">เครื่องมือ</a>
            </li>
            <li class="nav-item" @click="selectTab('help')">
              <a class="nav-link text-white" href="#">ช่วยเหลือ</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <b-container fluid>
    <!-- ปุ่มเมนูย่อย -->
    <div v-if="selectedTab === 'customers'" class="mt-3">
      <button @click="selectTab('CustomerInfo')" class="btn custom-btn">
        ข้อมูลลูกค้า
      </button>
      <button @click="addSaleTab" class="btn custom-btn">
        ขายสินค้า
      </button>
    </div>

    <div v-if="selectedTab === 'products'" class="mt-3">
      <button @click="selectTab('productInfo')" class="btn custom-btn">
        ข้อมูลสินค้า
      </button>
    </div>

    <!-- แสดงเนื้อหา tab ย่อย -->
    <div class="tab-content mt-4">
      <div v-if="selectedTab === 'home'">
        <h4>หน้าหลัก</h4>
      </div>

      <div v-if="selectedTab === 'productInfo'">
        <product-crud />
      </div>

      <div v-if="selectedTab === 'CustomerInfo'">
        <customer />
      </div>

      <div v-if="selectedTab === 'tools'">
        <SearchInvoice />
      
      </div>

      <div v-if="selectedTab === 'help'">
     
      </div>
    </div>

    <!-- Tabs ขายสินค้า (ไดนามิก) -->
    <div v-if="saleTabs.length > 0 && selectedTab === 'CustomerSale'" class="mt-4">
      <b-card no-body>
        <b-tabs card>
          <b-tab
            v-for="(tab, index) in saleTabs"
            :key="tab.id"
            :title="tab.title"
            :active="index === saleTabs.length - 1"
          >
            <template #title>
              <span>{{ tab.title }}</span>
              <span
                style="margin-left: 10px; cursor: pointer; color: red;"
                @click.stop="closeSaleTab(tab.id)"
              >
                &times;
              </span>
            </template>
            <SaleProduct />
          </b-tab>
        </b-tabs>
      </b-card>
    </div>
  </b-container>
  </div>
</template>

<script>
import Customer from './Customer.vue'
import ProductCrud from './ProductCrud.vue'
import SaleProduct from './SaleProduct.vue'
import SearchInvoice from './SearchInvoice.vue' // import SearchInvoice component

export default {
  components: { Customer, ProductCrud, SaleProduct, SearchInvoice },
  name: "NavBar",
  data() {
    return {
      selectedTab: "home",
      saleTabs: [] // เก็บแท็บขายสินค้า
    };
  },
  methods: {
    selectTab(tab) {
      this.selectedTab = tab;
    },
    addSaleTab() {
      // เพิ่มแท็บขายสินค้าใหม่
      const newId = this.saleTabs.length + 1;
      this.saleTabs.push({
        id: newId,
        title: `ขายสินค้า #${newId}`
      });
      // แสดงแท็บขายสินค้าเมื่อกดปุ่ม
      this.selectedTab = 'CustomerSale';
    },
    closeSaleTab(tabId) {
      // ลบแท็บที่ถูกเลือกออกจาก saleTabs
      this.saleTabs = this.saleTabs.filter(tab => tab.id !== tabId);
    }
  }
};
</script>

<style scoped>
.navbar-nav .nav-item .nav-link {
  font-weight: bold;
}
.navbar-nav .nav-item.active .nav-link {
  color: #fff !important;
  background-color: #0056b3 !important;
}

button {
  margin: 5px 5px 5px 0;
}

.custom-btn {
  background-color: #add8e6 !important;
  color: #0000ff !important;
  border: 1px solid #add8e6 !important;
  height: 80px;
  font-size: 16px;
}
</style>
