<template >
    <el-row class="p-10">
        <el-col :span="6">
            <div style="height: 300px">
            <el-steps direction="vertical" :active="activeTab">
                <el-step title="Step 1" description="Intial setup"/>
                <el-step title="Step 2" description="Connect site" />
                <el-step title="Step 3" description="Build App"/>
                <el-step title="Step 4" description="Done! Download your App"/>
            </el-steps>
            </div>
        </el-col>
        <el-col id="dashboard-content" :span="18">
            <div v-if="activeTab==0">
                <div class="text-2xl  mb-2">Prerequisite</div>
                <div class=" text-sm">We JWT Auth plugin for app to provide login/register functionality</div>
                <div class="text-sm mb-10">It is verified by us and plugin is safe to install</div>
                <div class="mb-2 text-xl">Step 1</div>
                <el-button @click="installPlugin">Install JWT Auth Plugin</el-button>
                <div class="mb-2 mt-10 text-xl">Step 2</div>
                <div class=" text-sm mb-2">Add this line in wp-config.php file. Replace 'your-secret-key' with your own</div>
                <div class="mb-5">
                    <code class="p-2 mt-1">
                        define('JWT_AUTH_SECRET_KEY', 'your-secret-key');
                    </code>
                </div>
                <el-button @click="verifyWpConfig">Verify WP-Config</el-button>
            </div>
            <div v-if="activeTab==1">
                <div class="text-2xl mb-2">Link to AppCraftify</div>
                <div class=" text-sm">We will attempt to link your side in AppCraftify dashboard</div>
                <div class=" text-sm mb-10">You will be able to build your app after that</div>
                <el-button @click="addSiteHandler">Add site</el-button>
                <el-button @click="checkSiteStatus">Check status</el-button>
            </div>
            <div v-if="activeTab==2">
                <div class="text-2xl mb-2">Configure and Build</div>
                <div class=" text-sm">Edit default setting as per your need</div>
                <div class=" text-sm mb-10">After you submit build you should get email from us in 24hrs with app link</div>
                <el-button @click="gotoBuilder">Go to builder</el-button>
                <el-button @click="checkBuildStatus">Check status</el-button>
            </div>
            <div v-if="activeTab==3">
                <div class="text-2xl mb-2">Your app is ready</div>
                <div class=" text-sm">You can download it from AppCraftify dashboard</div>
                <div class=" text-sm mb-10">After you submit build you should get email from us in 24hrs with app link</div>
            </div>
        </el-col>
    </el-row>

  </template>
  

<script>
import { mapWritableState, mapActions } from "pinia";
import { appStore } from "../Bits/store";
import { ElLoading, ElMessage } from 'element-plus'
export default {
    name: "Dashboard",
    components: {
    },
    computed: {
        ...mapWritableState(appStore, [
            'settings'
        ])
    },
    data(){
        return {
            activeTab:0,
            isJWTInstalled: false
        }
    },
    mounted(){
        if(AppCraftifyAdmin.isJWTInstalled){
            this.activeTab+=1
            if(this.settings.isSiteLinked){
                this.activeTab+=1
                if(this.settings.isAppBuilt){
                    this.activeTab+=1
                }
            }
        }   
    },
    methods:{
        ...mapActions(appStore, [
            'jwtPluginInstall','isJWTAuthSecretKeyDefined','getSettings'
        ]),
        nextStep(){
            if(this.activeTab>=2) this.activeTab=0
            this.activeTab+=1
        },
        addSiteHandler(){
            open("https://appcraftify.com")
        },
        async checkSiteStatus(){
            var loading = ElLoading.service({
                target:"#dashboard-content",
                lock: true,
                text: 'Checking status...',
            })
            await this.getSettings()
            if(this.settings.isSiteLinked){
                this.activeTab+=1
                ElMessage({
                    message: "Site linked",
                    type: 'success',
                    offset: 50
                })
            }
            else{
                ElMessage({
                    message: "Not yet linked",
                    type: 'error',
                    offset: 50
                })
            }
            loading.close()
        },
        async checkBuildStatus(){
            var loading = ElLoading.service({
                target:"#dashboard-content",
                lock: true,
                text: 'Checking status...',
            })
            await this.getSettings()
            if(this.settings.isAppBuilt){
                this.activeTab+=1
                ElMessage({
                    message: "Congratulation your app is ready",
                    type: 'success',
                    offset: 50
                })
            }
            else{
                ElMessage({
                    message: "You have not built your app yet",
                    type: 'error',
                    offset: 50
                })
            }
            loading.close()
        },
        gotoBuilder(){
            open("https://appcraftify.com")
        },
        async installPlugin(){
            var loading = ElLoading.service({
                target:"#dashboard-content",
                lock: true,
                text: 'Installing...',
            })
            if(await this.jwtPluginInstall()){
                this.isJWTInstalled = true

            }
            loading.close()
        },
        async verifyWpConfig(){
            var loading = ElLoading.service({
                target:"#dashboard-content",
                lock: true,
                text: 'Checking...',
            })
            if(await this.isJWTAuthSecretKeyDefined() && this.isJWTInstalled){
                this.activeTab+=1
            }
            loading.close()
        }
    }
}
</script>
