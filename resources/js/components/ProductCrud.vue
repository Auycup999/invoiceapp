<template>
  <div>
    <h2>ตารางข้อมูลสินค้า</h2>

    <!-- ปุ่มค้นหาสินค้า -->
    <div class="mb-3 d-flex justify-content-end">
      <input
        v-model="searchQuery"
        type="text"
        class="form-control w-25"
        placeholder="ค้นหาสินค้า"
        @input="fetchProducts"
      />
    </div>

    <!-- ตารางแสดงข้อมูลสินค้า -->
    <table class="table mt-4">
      <thead>
        <tr>
          <th>รหัสสินค้า</th>
          <th>ชื่อสินค้า</th>
          <th>ราคาขาย</th>
          <th>หน่วย</th>
          <th>การจัดการ</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(product, index) in products" :key="index">
          <td>
            <input
              v-model="product.barcode"
              type="text"
              class="form-control"
              @click="setActiveCell(index, 'barcode')"
           readonly />
          </td>
          <td>
            <input
              v-model="product.name"
              type="text"
              class="form-control"
              @click="setActiveCell(index, 'name')"
            />
          </td>
          <td>
            <input
              v-model="product.price"
              type="number"
              class="form-control"
              @click="setActiveCell(index, 'price')"
            />
          </td>
          <td>
            <input
              v-model="product.unit"
              type="text"
              class="form-control"
              @click="setActiveCell(index, 'unit')"
            />
          </td>
          <td>
            <!-- ปุ่มบันทึกเมื่อคลิกที่ช่อง -->
            <button
              v-if="product.isEditing"
              @click="saveProduct(index)"
              class="btn btn-success"
            >
              บันทึก
            </button>
            <button
              v-if="product.isEditing"
              @click="cancelEdit(index)"
              class="btn btn-secondary"
            >
              ยกเลิก
            </button>
            <button
              @click="deleteProduct(product.id)"
              class="btn btn-danger"
            >
              ลบ
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- ปุ่มเพิ่มสินค้า -->
    <button class="btn btn-primary mt-3" @click="addNewProduct">
      เพิ่มสินค้า
    </button>

    <!-- Pagination -->
    <div class="mt-3">
      <button
        class="btn btn-secondary"
        :disabled="page === 1"
        @click="fetchProducts(page - 1)"
      >
        ก่อนหน้า
      </button>
      <span>หน้า {{ page }} จาก {{ totalPages }}</span>
      <button
        class="btn btn-secondary"
        :disabled="page === totalPages"
        @click="fetchProducts(page + 1)"
      >
        ถัดไป
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      activeCell: null, // เก็บตำแหน่งของเซลล์ที่ถูกเลือก
      products: [], // ข้อมูลสินค้าที่ดึงจากฐานข้อมูล
      searchQuery: "", // คำค้นหาสินค้า
      page: 1, // หน้าเริ่มต้น
      totalPages: 1, // จำนวนหน้าทั้งหมด
      perPage: 10, // จำนวนสินค้าในแต่ละหน้า
    };
  },
  methods: {
    // ฟังก์ชันที่ใช้ในการเลือกเซลล์
    setActiveCell(rowIndex, column) {
        this.activeCell = { rowIndex, column };
        this.products[rowIndex].isEditing = true; // แสดงปุ่มบันทึกเมื่อคลิกที่ช่อง
    },
    // ฟังก์ชันเพิ่มสินค้าใหม่
    addNewProduct() {
        const newBarcode = this.generateBarcode(); // สร้างรหัสสินค้าใหม่
        this.products.push({
            barcode: newBarcode,  // เพิ่มรหัสสินค้าใหม่
            name: "",
            price: 0,
            unit: "",
            isNew: true, // กำหนดให้แถวนี้เป็นแถวใหม่ที่ต้องการบันทึก
            isEditing: false, // เพิ่มสถานะ isEditing เพื่อจัดการการแสดงปุ่มบันทึก
        });
    },
    // ฟังก์ชันสร้างรหัสสินค้าใหม่
    generateBarcode() {
        const lastProduct = this.products[this.products.length - 1];
        if (lastProduct) {
            const lastBarcode = lastProduct.barcode;
            const lastNumber = parseInt(lastBarcode.split("-")[1]) || 0;
            return `P-${lastNumber + 1}`;  // สร้างรหัสสินค้าโดยการเพิ่ม 1
        }
        return "P-1";  // ถ้าไม่มีสินค้าเลย ให้เริ่มจาก P-1
    },
    // ฟังก์ชันบันทึกสินค้าใหม่ลงฐานข้อมูล
    saveProduct(index) {
        const product = this.products[index];
        if (product.isNew) {
            // บันทึกสินค้าใหม่
            axios
                .post("/api/products", {
                    barcode: product.barcode,
                    name: product.name,
                    price: product.price,
                    unit: product.unit,
                })
                .then(() => {
                    this.fetchProducts(); // โหลดข้อมูลใหม่หลังการบันทึก
                    product.isEditing = false; // หลังจากบันทึกให้ยกเลิกสถานะ isEditing
                })
                .catch((error) => {
                    console.error(error);
                });
        } else {
            // แก้ไขสินค้า
            axios
                .put(`/api/products/${product.id}`, {
                    barcode: product.barcode,
                    name: product.name,
                    price: product.price,
                    unit: product.unit,
                })
                .then(() => {
                    this.fetchProducts(); // โหลดข้อมูลใหม่หลังการบันทึก
                    product.isEditing = false; // หลังจากบันทึกให้ยกเลิกสถานะ isEditing
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    },
    // ฟังก์ชันแก้ไขข้อมูล
    editProduct(index) {
        const product = this.products[index];
        product.isEditing = true;
    },
    // ฟังก์ชันยกเลิกการแก้ไข
    cancelEdit(index) {
        const product = this.products[index];
        product.isEditing = false;
        this.fetchProducts(); // โหลดข้อมูลใหม่หลังจากยกเลิกการแก้ไข
    },
    // ฟังก์ชันลบสินค้า
    deleteProduct(id) {
        axios
            .delete(`/api/products/${id}`)
            .then(() => {
                this.fetchProducts(); // โหลดข้อมูลใหม่หลังจากลบสินค้า
            })
            .catch((error) => {
                console.error(error);
            });
    },
    // ฟังก์ชันดึงข้อมูลสินค้าจากฐานข้อมูล
    fetchProducts(page = 1) {
        this.page = page; // กำหนดหมายเลขหน้าปัจจุบัน
        axios
            .get(`/api/products?page=${this.page}&per_page=${this.perPage}&search=${this.searchQuery}`)
            .then((response) => {
                this.products = response.data.data; // รับข้อมูลสินค้าที่ถูกแบ่งหน้า
                this.totalPages = response.data.last_page; // รับจำนวนหน้าทั้งหมดจากข้อมูลที่ส่งกลับ
            })
            .catch((error) => {
                console.error(error);
            });
    },
},
  mounted() {
    this.fetchProducts(); // โหลดข้อมูลสินค้าทันทีเมื่อเริ่มต้น
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

th {
  background-color: #f4f4f4;
}

input[type="text"],
input[type="number"] {
  font-size: 16px; /* ขนาดตัวอักษร */
  font-weight: bold; /* ตัวหนา */
  text-align: center;
}

button {
  margin-top: 20px;
}
button.btn-danger {
  font-size: 12px; /* ขนาดตัวอักษรของปุ่มลบ */
  padding: 5px 10px; /* ปรับขนาดปุ่ม */
  margin-top: 5px; /* ขยับปุ่มขึ้นไปเล็กน้อย */
}

.table td button {
  margin-top: 5px; /* ลดระยะห่างจากช่องเซลล์ */
}
</style>
