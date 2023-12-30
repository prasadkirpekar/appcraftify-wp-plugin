<template>
  <div class="w-full py-8 px-5 bg-white shadow-sm">
      <div class="flex justify-between">
        <div class="flex">
          <img class="h-10 hidden w-10 mr-2" />
          <div>
            <h3 class="m-auto text-2xl font-semibold text-gray-700">
              AppCraftify
            </h3>
            <div class="m-auto text-base text-gray-500">
              WordPress app builder
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <el-tabs v-if="isReady" style="height: 70vh;" type="border-card" v-model="activeName" class="demo-tabs">
        <el-tab-pane class="h-screen80 overflow-scroll" label="Dashboard" name="dashboard">
            <Dashboard></Dashboard>
          </el-tab-pane>
          <el-tab-pane class="h-screen80 overflow-scroll" label="Settings" name="settings">
            <Settings></Settings>
          </el-tab-pane>
          <el-tab-pane class="h-screen80 overflow-scroll" label="About" name="about">
            <Contact></Contact>
          </el-tab-pane>
    </el-tabs>
    </div>
</template>
<script>
import { mapWritableState, mapActions } from "pinia";
import { appStore } from "../Bits/store";
import Dashboard from './Dashboard.vue';
import Settings from './Settings.vue';
import Contact from './Contact.vue'
export default {
  name: "Admin",
  components: {
    Dashboard,
    Settings,
    Contact
  },
  data() {
    return {
      activeName: 'dashboard',
      isReady:false
    };
  },
  async mounted() {
    await this.getSettings();
    this.isReady=true
  },
  methods:{
    ...mapActions(appStore, [
            'getSettings'
        ]),
  }
};
</script>
