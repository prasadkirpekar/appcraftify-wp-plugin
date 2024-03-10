import routes from './routes';
import { createWebHashHistory, createRouter } from 'vue-router'
import AppCraftify from './Bits/AppMixins';

var router = createRouter({
    history: createWebHashHistory(),
    routes
});


var framework = new AppCraftify();

framework.app.config.globalProperties.appVars = window.AppCraftifyAdmin;

window.AppCraftifyApp = framework.app.use(router).mount('#AppCraftify_app');

router.afterEach((to, from) => {
    jQuery('.AppCraftify_menu_item').removeClass('active');
    let active = to.meta.active;
    if(active) {
        jQuery('.AppCraftify_main-menu-items').find('li[data-key='+active+']').addClass('active');
    }
});

//update nag remove from admin, You can remove if you want to show notice on admin
jQuery('.update-nag,.notice, #wpbody-content > .updated, #wpbody-content > .error').remove();
