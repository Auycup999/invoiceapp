<template>
  <div>
    <!-- ฟอร์มด้านบนซ้าย -->
    <div class="row">
      <div class="col">
        <label for="invoiceNumber">เลขที่เอกสาร</label>
        <input v-model="invoice_number" type="text" class="form-control" readonly />

        <label for="documentDate">วันที่เอกสาร</label>
        <input v-model="invoice.date" type="date" class="form-control" />

        <label for="customer">เลือกลูกค้า</label>
<v-select
  v-model="selectedCustomer"
  :options="customers"
  :get-option-label="getCustomerLabel"
  @input="selectCustomer"
  label="customer_name"
  placeholder="ค้นหาลูกค้า"
/>
<div v-if="selectedCustomer">
  <p>ลูกค้า: {{ selectedCustomer.customer_name }}</p>
</div>
      </div>

      <!-- <div class="col" style="margin-left: 10px;">
        <input type="radio" id="cash" v-model="invoice.paymentMethod" value="cash" /> เงินสด
        <input type="radio" id="credit" v-model="invoice.paymentMethod" value="credit" /> เครดิต
        <br />
        <br />
      </div> -->
      <div class="col">
        <!-- แสดงผลรวมทั้งหมดใน textarea -->
        <textarea v-model="totalAmount" cols="60" rows="10" readonly></textarea>
      </div>
    </div>

    <!-- ตารางแสดงข้อมูลสินค้า -->
    <table class="table mt-4">
      <thead>
        <tr>
          <th>รหัสสินค้า</th>
          <th>ชื่อสินค้า</th>
          <th>ราคาขาย</th>
          <th>จำนวน</th>
          <th>รวม</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in saleItems" :key="index">
          <td>
            <input v-model="item.barcode" type="text" class="form-control" :placeholder="item.barcode || 'กรอกรหัสสินค้า'" readonly />
          </td>

          <!-- ช่องค้นหาสินค้า -->
          <td>
            <v-select
              v-model="item.product"
              :options="products"
              :get-option-label="getProductLabel"
              @input="updateProductDetails(item)"
              label="name"
              placeholder="เลือกสินค้า"
            />
          </td>
          <td>
            <input v-model="item.price" type="number" class="form-control" placeholder="กรอกราคาขาย" readonly />
          </td>
          <td>
            <input v-model="item.quantity" type="number" min="1" class="form-control" placeholder="กรอกจำนวน" />
          </td>
          <td>{{ item.price * item.quantity }}</td>
        </tr>
      </tbody>
    </table>

    <!-- ปุ่มเพิ่มสินค้า -->
    <button class="btn btn-primary mt-3" @click="addProduct">เพิ่มสินค้า</button>

    <!-- ปุ่มบันทึกการขาย -->
    <button class="btn btn-success mt-3" @click="saveSale">บันทึกการขาย</button>
  </div>
</template>

<script>
import axios from "axios";
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

export default {
  components: {
    vSelect
  },
  data() {
    return {
      invoice: {
        number: "",
        date: "",
        dueDate: "",
        paymentMethod: "cash", // เงินสดเป็นค่าเริ่มต้น
      },
      selectedCustomer: null, // ลูกค้าที่เลือกจาก select box
      customers: [], // รายชื่อลูกค้าที่ดึงจาก API
      saleItems: [
        { barcode: "", product: null, price: 0, quantity: 1 }, // ช่องแรกเริ่มต้น
      ], // รายการสินค้าที่ขาย
      products: [], // รายการสินค้าที่ดึงจาก API
      lastInvoiceNumber: 0, // หมายเลขเอกสารล่าสุด
    };
  },
  computed: {
    // คำนวณยอดรวมของสินค้าทั้งหมด
    totalAmount() {
      return this.saleItems.reduce((total, item) => {
        return total + item.price * item.quantity;
      }, 0).toFixed(2); // ทศนิยม 2 ตำแหน่ง
    },
  },
  methods: {
    getCustomerLabel(customer) {
    return customer.customer_name; // หรือจะใช้ customer.customer_name เพื่อแสดงชื่อ
  },

  // ฟังก์ชันเพื่อเลือกลูกค้า (ใช้เมื่อเลือกลูกค้าจาก v-select)
  selectCustomer(customer) {
    this.selectedCustomer = customer; // ตั้งค่าลูกค้าที่เลือก
  },

  // ฟังก์ชันสร้างหมายเลขเอกสาร
  generateInvoiceNumber() {
    const newInvoiceNumber = this.lastInvoiceNumber + 1;
    return `${String(newInvoiceNumber).padStart(6, '0')}`; // เติม 0 ด้านหน้าให้ครบ 6 หลัก
  },

  // ฟังก์ชันเพื่อเลือกลูกค้า (ข้อมูลลูกค้าจะถูกดึงจาก API)
  fetchCustomers() {
    axios.get("/api/GetCustomer_sales").then((response) => {
      this.customers = response.data; // ดึงข้อมูลลูกค้าทั้งหมด
    });
  },
  
  // ฟังก์ชันดึงข้อมูลสินค้าจาก API
  fetchProducts() {
    axios.get("/api/GetProduct_sales").then((response) => {
      this.products = response.data; // ดึงข้อมูลสินค้า
    });
  },
  // ฟังก์ชันที่ใช้เพื่ออัปเดตรายละเอียดสินค้าเมื่อเลือกสินค้า
  updateProductDetails(item) {
    if (item.product) {
      item.barcode = item.product.barcode;
      item.price = item.product.price;
    }
  },
    // ฟังก์ชันสร้างหมายเลขเอกสาร
    generateInvoiceNumber() {
      const newInvoiceNumber = this.lastInvoiceNumber + 1;
      return `${String(newInvoiceNumber).padStart(6, '0')}`; // เติม 0 ด้านหน้าให้ครบ 6 หลัก
    },

    // ฟังก์ชันเพื่อเลือกลูกค้า (ข้อมูลลูกค้าจะถูกดึงจาก API)
    fetchCustomers() {
      axios.get("/api/GetCustomer_sales").then((response) => {
        this.customers = response.data; // ดึงข้อมูลลูกค้าทั้งหมด
      });
    },

    // ฟังก์ชันดึงข้อมูลสินค้าจาก API
    fetchProducts() {
      axios.get("/api/GetProduct_sales").then((response) => {
        this.products = response.data; // ดึงข้อมูลสินค้า
      });
    },

    // เพิ่มสินค้าไปยังรายการขาย
    addProduct() {
      this.saleItems.push({
        barcode: "",
        product: null,
        price: '',
        quantity: 1,
      });
    },

    // ฟังก์ชันที่ใช้เพื่ออัปเดตรายละเอียดสินค้าเมื่อเลือกสินค้า
    updateProductDetails(item) {
  if (item.product) {
    item.barcode = item.product.barcode;
    item.price = item.product.price;
    
    // เพิ่มแถวใหม่เมื่อเลือกสินค้าในช่องนี้
    if (this.saleItems[this.saleItems.length - 1] === item) {
      this.addProduct(); // เพิ่มแถวสินค้าใหม่
    }
  }
},

    // ลบสินค้าออกจากรายการขาย
    removeItem(index) {
      this.saleItems.splice(index, 1);
    },

    // บันทึกการขาย
    saveSale() {
  // กรองสินค้าเพียงรายการที่มีข้อมูล
  const validSaleItems = this.saleItems.filter(item => item.product && item.product.id && item.price > 0 && item.quantity > 0);
  
  if (validSaleItems.length === 0) {
    alert("กรุณากรอกข้อมูลสินค้าให้ครบถ้วน");
    return;
  }

  const saleData = {
    customer_id: this.selectedCustomer.id,
    payment_method: this.invoice.paymentMethod,
    items: validSaleItems.map((item) => ({
      product_id: item.product.id,
      quantity: item.quantity,
      price: item.price,
      name: item.product.name,
    })),
    total: this.totalAmount,
    invoice_number: this.generateInvoiceNumber(),
    invoice_date: this.invoice.date,
    due_date: this.invoice.dueDate,
  };

  axios
    .post("/api/sales", saleData)
    .then((response) => {
      alert("บันทึกการขายสำเร็จ!");
      this.saleItems = [];
      this.selectedCustomer = null;
      // this.invoice = {};
      axios.get("/api/getLatestInvoiceNumber").then((response) => {
      this.lastInvoiceNumber = response.data.last_invoice_number;
      this.invoice_number = this.generateInvoiceNumber();
    });
      this.lastInvoiceNumber = response.data.last_invoice_number;
      window.open(response.data.url, '_blank');
    })
    .catch((error) => {
      console.error(error);
      alert("เกิดข้อผิดพลาดในการบันทึกการขาย");
    });
},
  },
  mounted() {
    const today = new Date();
    this.invoice.date = today.toISOString().substr(0, 10);
    this.invoice.dueDate = today.toISOString().substr(0, 10);

    this.fetchCustomers();
    this.fetchProducts();

    axios.get("/api/getLatestInvoiceNumber").then((response) => {
      this.lastInvoiceNumber = response.data.last_invoice_number;
      this.invoice_number = this.generateInvoiceNumber();
    });
  },
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
  text-align: center;
  border: 1px solid #ddd;
}

button {
  margin-top: 10px;
}

input[type="text"],
input[type="number"] {
  padding: 5px;
  font-size: 12px;
  text-align: center;
}

input[type="radio"] {
  margin-right: 5px;
}

textarea {
  margin-top: 10px;
  width: 100%;
  font-size: 30px;
  text-align: center;
  height: 200px;
  padding-top: 50px;
}
</style>
