<template >
    <el-row class="p-10">
        <el-col :span="6">
            <div style="height: 300px">
            <el-steps direction="vertical" :active="activeTab">
                <el-step title="Step 1" description="Intial setup"/>
                <el-step title="Step 2" description="Connect site" />
                <el-step title="Step 3" description="Build App"/>
            </el-steps>
            </div>
        </el-col>
        <el-col :span="18">
            <div v-if="activeTab==0">
                <div class="text-2xl  mb-2">Prerequisite</div>
                <div class=" text-sm">We JWT Auth plugin for app to provide login/register functionality</div>
                <div class="text-sm mb-10">It is verified by us and plugin is safe to install</div>
                
                <el-button >Install JWT Auth Plugin</el-button>
            </div>
            <div v-if="activeTab==1">
                <div class="text-2xl mb-2">Link to AppCraftify</div>
                <div class=" text-sm">We will attempt to link your side in AppCraftify dashboard</div>
                <div class=" text-sm mb-10">You will be able to build your app after that</div>
                <el-button>Add site</el-button>
            </div>
            <div v-if="activeTab==2">
                <div class="text-2xl mb-2">Configure and Build</div>
                <div class=" text-sm">Edit default setting as per your need</div>
                <div class=" text-sm mb-10">After you submit build you should get email from us in 24hrs with app link</div>
                <el-button>Add site</el-button>
            </div>
            <el-button @click="nextStep">Next</el-button>
        </el-col>
    </el-row>

  </template>
  

<script>

export default {
    name: "Dashboard",
    components: {
    },
    data(){
        return {
            activeTab:0
        }
    },
    methods:{
        nextStep(){
            if(this.activeTab>=2) this.activeTab=0
            this.activeTab+=1
        }
    }
}
</script>
