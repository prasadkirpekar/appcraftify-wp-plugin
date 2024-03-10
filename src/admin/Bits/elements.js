import { createApp } from 'vue';
import { createPinia } from 'pinia'
import ElementPlus from 'element-plus'

import '../../assets/tailwind.css';
import '../../assets/admin.css';
import 'element-plus/dist/index.css'


var app = createApp({});
var pinia = createPinia()
app.use(pinia)
app.use(ElementPlus)
export default app;
