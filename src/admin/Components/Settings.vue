<template>
    <div>
        <!-- element plus form with one text field and save button -->
        <el-form class="w-2/4" label-position="left" label-width="150px" ref="form" :model="form">
            
            <el-form-item label="Enable">
                <el-switch v-model="settings.enabled"></el-switch>
                </el-form-item>
                <el-form-item label="Enable CORS">
                <el-switch v-model="settings.enableCORS" @change="changeCORSSettings"></el-switch>
                </el-form-item>
            <el-form-item label="API Key">
                <el-row class="flex">
                <el-col :span="22">
                    
                        <el-input disabled v-model="settings.apiKey" placeholder="API Key"></el-input>
                    
                </el-col>
                <el-col :span="2">
                    
                        <el-button  @click="copyToClipboard(settings.apiKey)">Copy</el-button>
                    
                </el-col>
                
            </el-row>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="updateSettings">Save</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<script>
import { mapWritableState, mapActions } from "pinia";
import { appStore } from "../Bits/store";
export default {
    name: "Settings",
    components: {
    },
    computed: {
    ...mapWritableState(appStore, ["settings"]),
    },
    methods: {
        ...mapActions(appStore, [
            'updateSettings',
            'updateCORSSettings'
        ]),
        copyToClipboard(text){
            navigator.clipboard.writeText(text);
            this.$message({
                        type: 'success',
                        message: 'Copied to clipboard'
                    });
        },
        changeCORSSettings(corsState){
            this.updateCORSSettings(corsState)
        }
    }
}
</script>