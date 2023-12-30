<template>
  <div class="p-10 text-bas">
    <el-descriptions
    :column="1" border title="Site information">
    <el-descriptions-item label="Site Url">{{ AppCraftifyAdmin.siteUrl }}</el-descriptions-item>
    <el-descriptions-item label="Supported app version">0.1.0 and above (This is app version supported by AppCraftify platform)</el-descriptions-item>
    <el-descriptions-item label="WooCommerce Active">{{ wooCommerceActive }}</el-descriptions-item>
  </el-descriptions>
  </div>
</template>

<script>
import { mapWritableState, mapActions } from "pinia";
import { appStore } from "../Bits/store";
export default {
    name: 'Contact',
    computed: {
        ...mapWritableState(appStore, [
            
        ]),
        AppCraftifyAdmin(){
            return AppCraftifyAdmin;
        },
        wooCommerceActive(){
            return this.AppCraftifyAdmin.isWooInstalled? "Yes":"No";
        }
    },
    data() {
        return {
            page: "contact"
        }
    },

}
</script>