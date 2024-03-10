import { defineStore } from 'pinia';
import axios from 'axios';
import { ElMessage } from 'element-plus'

export var appStore = defineStore('app', {
    state: () => ({
        settings: {
            enabled: true,
            apiKey: '',
            isSiteLinked:false,
            isAppBuilt: false,
            enableCORS: false,
        }
    }),

    actions: {
        mergeSettings(data){
            var merged = {};
            Object.keys(this.settings).forEach(k => {
                if(this.settings[k]!=null){
                    merged[k] = this.settings[k]
                }
            });
            Object.keys(data).forEach(k => {
                if(this.settings[k]!=null){
                    merged[k] = data[k]
                }
            });
            this.settings = merged
        },
        async getSettings() {
            let res = await axios
                .post(
                    AppCraftifyAdmin.ajaxurl,
                    {
                        action: "AppCraftify_getSettings",
                    },
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded;",
                        },
                    }
                )
            if (res.status == 200) {
                this.mergeSettings(res.data.data)
            }
        },

        async updateSettings() {
            let res = await axios
                .post(
                    AppCraftifyAdmin.ajaxurl,
                   {
                        action: "AppCraftify_saveSettings",
                        nonce: AppCraftifyAdmin.AppCraftify_nonce,
                        settings:this.settings,
                    },
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded;",
                        },
                    },
                )
            if (res.status == 200 && res.data.success) {
                this.mergeSettings(res.data.data)
                ElMessage({
                    message: "Settings saved!",
                    type: 'success',
                    offset: 50
                })
                return true   
            }
            else{
                ElMessage({
                    message: "Unable to update!",
                    type: 'error',
                    offset: 50
                })
            }
            return false
        },

        async updateCORSSettings(corsState) {
            let res = await axios
                .post(
                    AppCraftifyAdmin.ajaxurl,
                   {
                        action: "AppCraftify_updateCORSSettings",
                        nonce: AppCraftifyAdmin.AppCraftify_nonce,
                        corsState:corsState,
                    },
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded;",
                        },
                    },
                )
            if (res.status == 200 && res.data.success) {
                this.mergeSettings(res.data.data)
                ElMessage({
                    message: "CORS setting upated!",
                    type: 'success',
                    offset: 50
                })
                return true   
            }
            else{
                ElMessage({
                    message: "Unable to update!",
                    type: 'error',
                    offset: 50
                })
            }
            return false
        },

        async jwtPluginInstall(){
            let res = await axios
                .post(
                    AppCraftifyAdmin.ajaxurl,
                   {
                        action: "AppCraftify_installAuthPluginInstall",
                        nonce: AppCraftifyAdmin.AppCraftify_nonce,
                    },
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded;",
                        },
                    },
                )
            if (res.status == 200) {
                ElMessage({
                    message: "Plugin installed!",
                    type: 'success',
                    offset: 50
                })
                return true   
            }
            else{
                ElMessage({
                    message: "Unable to install!",
                    type: 'error',
                    offset: 50
                })
            }
            return false
        },

        async isJWTAuthSecretKeyDefined(){
            let res = await axios
                .post(
                    AppCraftifyAdmin.ajaxurl,
                    {
                        action: "AppCraftify_isJWTAuthSecretKeyDefined",
                        nonce: AppCraftifyAdmin.AppCraftify_nonce,
                    },
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded;",
                        },
                    },
                )
            if (res.status == 200  && res.data.success  && res.data.data) {
                ElMessage({
                    message: "Verified successfully!",
                    type: 'success',
                    offset: 50
                })
                return true   
            }
            else{
                ElMessage({
                    message: "Unable to verify!",
                    type: 'error',
                    offset: 50
                })
            }
            return false
        }

        
    }

});