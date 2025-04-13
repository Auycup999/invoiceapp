<template>
    <div>
      <h2>ค้นหาบิล</h2>
  
      <!-- Input สำหรับพิมพ์ชื่อของลูกค้า -->
      <input v-model="searchQuery" @input="searchInvoices" placeholder="พิมพ์ชื่อลูกค้าเพื่อค้นหา" class="form-control" />
  
      <!-- แสดงผลลัพธ์ของการค้นหา -->
      <div v-if="invoices.length">
        <h3>ผลการค้นหา</h3>
        <table class="table">
          <thead>
            <tr>
              <th>เลขที่เอกสาร</th>
              <th>วันที่</th>
              <th>ยอดรวม</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="invoice in invoices" :key="invoice.invoice_number">
              <td>{{ invoice.invoice_number }}</td>
              <td>{{ invoice.created_at }}</td>
              <td>{{ invoice.total }}</td>
              <td>
                 <!-- ปุ่มเปิดไฟล์ PDF -->
  <a :href="`/storage/invoices/invoice_${invoice.invoice_number}.pdf`" target="_blank" class="btn btn-primary">
    เปิดบิล
  </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <p>ไม่มีผลลัพธ์</p>
      </div>
    </div>
  </template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      searchQuery: "", // ค่าที่พิมพ์เพื่อค้นหา
      invoices: [] // รายการบิลที่ค้นพบ
    };
  },
  methods: {
    // ค้นหาบิลเมื่อมีการพิมพ์
    searchInvoices() {
      if (this.searchQuery.length > 0) {
        axios.get(`/api/search-invoices?customer_name=${this.searchQuery}`)
          .then((response) => {
            this.invoices = response.data; // อัพเดตผลลัพธ์
          })
          .catch((error) => {
            console.error(error);
          });
      } else {
        this.invoices = []; // ถ้าไม่มีการพิมพ์ใดๆ ให้เคลียร์ผลลัพธ์
      }
    },

    // ฟังก์ชันสำหรับพิมพ์บิลเป็น PDF
    printInvoice(invoiceNumber) {
      // ส่งคำร้องขอไปยัง API เพื่อดาวน์โหลด PDF
      axios.get(`/api/print-invoice/${invoiceNumber}`, { responseType: 'blob' })
        .then((response) => {
          // สร้าง URL สำหรับ Blob ที่ได้
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', `invoice_${invoiceNumber}.pdf`); // กำหนดชื่อไฟล์
          document.body.appendChild(link);
          link.click(); // เรียกใช้งานการดาวน์โหลดไฟล์
        })
        .catch((error) => {
          console.error('Error printing invoice:', error);
        });
    }
  }
};
</script>           
  