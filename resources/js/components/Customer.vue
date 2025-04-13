<template>
  <div>
    <h2>ตารางข้อมูลลูกค้า</h2>

    <!-- ปุ่มค้นหาลูกค้า -->
    <div class="mb-3 d-flex justify-content-end">
      <input
        v-model="searchQuery"
        type="text"
        class="form-control w-50"
        placeholder="ค้นหาลูกค้า"
        @input="fetchCustomers"
      />
    </div>

    <!-- ตารางแสดงข้อมูลลูกค้า -->
    <table class="table mt-4">
      <thead>
        <tr>
          <th>รหัสลูกค้า</th>
          <th>ชื่อลูกค้า</th>
          <th>ที่อยู่</th>
          <th>โทรศัพท์</th>
          <th>หมายเหตุ</th>
          <th>การจัดการ</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(customer, index) in customers" :key="index">
          <td>
            <input
              v-model="customer.customer_code"
              type="text"
              class="form-control"
              @click="setActiveCell(index, 'customer_code')"
              readonly
            />
          </td>
          <td>
            <input
              v-model="customer.customer_name"
              type="text"
              class="form-control"
              @click="setActiveCell(index, 'customer_name')"
            />
          </td>
          <td>
            <input
              v-model="customer.address"
              type="text"
              class="form-control"
              @click="setActiveCell(index, 'address')"
            />
          </td>
          <td>
            <input
              v-model="customer.phone"
              type="text"
              class="form-control"
              @click="setActiveCell(index, 'phone')"
            />
          </td>
          <td>
            <input
              v-model="customer.notes"
              type="text"
              class="form-control"
              @click="setActiveCell(index, 'notes')"
            />
          </td>
          <td>
            <!-- ปุ่มบันทึกเมื่อคลิกที่ช่อง -->
            <button
              v-if="customer.isEditing"
              @click="saveCustomer(index)"
              class="btn btn-success"
            >
              บันทึก
            </button>
            <button
              v-if="customer.isEditing"
              @click="cancelEdit(index)"
              class="btn btn-secondary"
            >
              ยกเลิก
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- ปุ่มเพิ่มลูกค้า -->
    <button class="btn btn-primary mt-3" @click="addNewCustomer">
      เพิ่มลูกค้า
    </button>

    <!-- Pagination -->
    <div class="mt-3">
      <button
        class="btn btn-secondary"
        :disabled="page === 1"
        @click="fetchCustomers(page - 1)"
      >
        ก่อนหน้า
      </button>
      <span>หน้า {{ page }} จาก {{ totalPages }}</span>
      <button
        class="btn btn-secondary"
        :disabled="page === totalPages"
        @click="fetchCustomers(page + 1)"
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
      customers: [], // ข้อมูลลูกค้าที่ดึงจากฐานข้อมูล
      searchQuery: "", // คำค้นหาลูกค้า
      page: 1, // หน้าเริ่มต้น
      totalPages: 1, // จำนวนหน้าทั้งหมด
      perPage: 10, // จำนวนลูกค้าในแต่ละหน้า
    };
  },
  methods: {
    // ฟังก์ชันที่ใช้ในการเลือกเซลล์
    setActiveCell(rowIndex, column) {
      this.activeCell = { rowIndex, column };
      this.customers[rowIndex].isEditing = true; // แสดงปุ่มบันทึกเมื่อคลิกที่ช่อง
    },
    // ฟังก์ชันเพิ่มลูกค้าใหม่
    addNewCustomer() {
      const newCustomerCode = this.generateCustomerCode(); // สร้างรหัสลูกค้าใหม่
      this.customers.push({
        customer_code: newCustomerCode,  // เพิ่มรหัสลูกค้าใหม่
        customer_name: "",
        contact_person: "",
        address: "",
        phone: "",
        type: "",
        credit_limit: 0,
        notes: "",
        isNew: true, // กำหนดให้แถวนี้เป็นแถวใหม่ที่ต้องการบันทึก
        isEditing: false, // เพิ่มสถานะ isEditing เพื่อจัดการการแสดงปุ่มบันทึก
      });
    },
    // ฟังก์ชันสร้างรหัสลูกค้าใหม่
    generateCustomerCode() {
      const lastCustomer = this.customers[this.customers.length - 1];
      if (lastCustomer) {
        const lastCode = lastCustomer.customer_code;
        const lastNumber = parseInt(lastCode.slice(-3)); // ดึงตัวเลขท้ายจากรหัสลูกค้า เช่น '002' จาก 'C-002'
        return `C-${String(lastNumber + 1).padStart(3, '0')}`;  // เพิ่ม 1 และเติม 0 ให้ครบ 3 หลัก
      }
      return 'C-001';  // ถ้าไม่มีลูกค้าเลย ให้เริ่มจากรหัส 'C-001'
    },
    // ฟังก์ชันบันทึกลูกค้าใหม่ลงฐานข้อมูล
    saveCustomer(index) {
      const customer = this.customers[index];
      if (customer.isNew) {
        // บันทึกลูกค้าใหม่
        axios
          .post("/api/customers", {
            customer_code: customer.customer_code,
            customer_name: customer.customer_name,
            contact_person: customer.contact_person,
            address: customer.address,
            phone: customer.phone,
            type: customer.type,
            credit_limit: customer.credit_limit,
            notes: customer.notes,
          })
          .then(() => {
            this.fetchCustomers(); // โหลดข้อมูลใหม่หลังการบันทึก
            customer.isEditing = false; // หลังจากบันทึกให้ยกเลิกสถานะ isEditing
          })
          .catch((error) => {
            console.error(error);
          });
      } else {
        // แก้ไขลูกค้า
        axios
          .put(`/api/customers/${customer.id}`, {
            customer_code: customer.customer_code,
            customer_name: customer.customer_name,
            contact_person: customer.contact_person,
            address: customer.address,
            phone: customer.phone,
            type: customer.type,
            credit_limit: customer.credit_limit,
            notes: customer.notes,
          })
          .then(() => {
            this.fetchCustomers(); // โหลดข้อมูลใหม่หลังการบันทึก
            customer.isEditing = false; // หลังจากบันทึกให้ยกเลิกสถานะ isEditing
          })
          .catch((error) => {
            console.error(error);
          });
      }
    },
    // ฟังก์ชันแก้ไขข้อมูลลูกค้า
    editCustomer(index) {
      const customer = this.customers[index];
      customer.isEditing = true;
    },
    // ฟังก์ชันยกเลิกการแก้ไข
    cancelEdit(index) {
      const customer = this.customers[index];
      customer.isEditing = false;
      this.fetchCustomers(); // โหลดข้อมูลใหม่หลังจากยกเลิกการแก้ไข
    },
    // ฟังก์ชันดึงข้อมูลลูกค้าจากฐานข้อมูล
    fetchCustomers(page = 1) {
      this.page = page; // กำหนดหมายเลขหน้าปัจจุบัน
      axios
        .get(`/api/customers?page=${this.page}&per_page=${this.perPage}&search=${this.searchQuery}`)
        .then((response) => {
          this.customers = response.data.data; // รับข้อมูลลูกค้าที่ถูกแบ่งหน้า
          this.totalPages = response.data.last_page; // รับจำนวนหน้าทั้งหมดจากข้อมูลที่ส่งกลับ
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
  mounted() {
    this.fetchCustomers(); // โหลดข้อมูลลูกค้าทันทีเมื่อเริ่มต้น
  },
};
</script>

<style scoped>
/* ปรับขนาดของตาราง */
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
  padding: 3px; /* ลดระยะห่างภายใน input */
  font-size: 16px; /* ขนาดตัวอักษร */
  text-align: center;
  font-weight: bold; /* ตัวหนา */
}

button {
  margin-top: 20px;
}
</style>
