import { defineStore } from 'pinia';
import axios from 'axios';
import { ElMessage } from 'element-plus'

export const appStore = defineStore('app', {
    state: () => ({
        settings: {
            enabled: true,
            apiKey: '',
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
    }

});