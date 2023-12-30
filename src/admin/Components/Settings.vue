<template>
    <div>
        <!-- element plus form with one text field and save button -->
        <el-form class="w-2/4" label-position="left" label-width="150px" ref="form" :model="form">
            
            <el-form-item label="Enable">
                <el-switch v-model="settings.enabled"></el-switch>
                </el-form-item>
            <el-form-item label="API Key">
                <el-row class="flex">
                <el-col :span="22">
                    
                        <el-input disabled v-model="settings.apiKey" placeholder="API Key"></el-input>
                    
                </el-col>
                <el-col :span="2">
                    
                        <el-button  @click="submitForm('form')">Copy</el-button>
                    
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
            'updateSettings'
        ]),
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    this.$message({
                        type: 'success',
                        message: 'Settings saved successfully'
                    });
                } else {
                    this.$message({
                        type: 'error',
                        message: 'Please fill all the fields'
                    });
                    return false;
                }
            });
        }
    }
}
</script>