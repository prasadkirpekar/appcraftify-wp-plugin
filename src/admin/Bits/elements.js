import { createApp } from 'vue';
import { createPinia } from 'pinia'
import ElementPlus from 'element-plus'

import '../../assets/tailwind.css';
import '../../assets/admin.css';
import 'element-plus/dist/index.css'


const app = createApp({});
const pinia = createPinia()
app.use(pinia)
app.use(ElementPlus)
export default app;
