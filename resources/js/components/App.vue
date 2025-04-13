<template>
<div>
    <Navbar></Navbar>
</div>
  </template>
  
  <script>

import Product from "./Product.vue"; // ✅ Import Component
import ProductCrud from './ProductCrud.vue';

  export default {
    components: {
        Product,
        ProductCrud, // ✅ ต้องใส่ใน components: {}
  },
    data() {
      return {
        invoice: {
          customer_name: '',
          amount: '',
          generated: false
        }
      };
    },
    methods: {
      submitInvoice() {
        this.invoice.generated = true;
      }
    }
  };
  </script>
  