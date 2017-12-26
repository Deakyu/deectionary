import Vue from 'vue';
import VueRouter from 'vue-router';
import Login from '../views/Auth/Login.vue';
import Register from '../views/Auth/Register.vue';
import EmailCheck from '../views/Auth/EmailCheck.vue';
import Word from '../views/Word/Word.vue';
import Marked from '../views/Word/Marked.vue';
import Compose from '../views/Example/Compose.vue';
import Example from '../views/Example/Example.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {path: '/', component: Login, meta: {signedIn: false}},
        {path: '/register', component: Register, meta: {signedIn: false}},
        {path: '/emailcheck', component: EmailCheck, meta: {signedIn: false}},
        {path: '/search/:query', component: Word, meta: {signedIn: true}},
        {path: '/marked', component: Marked, meta: {signedIn: true}},
        {path: '/examples', component: Example, meta: {signedIn: true}},
        {path: '/examples/create', component: Compose, meta: {signedIn: true}},
    ],
});

// Router Guarding
router.beforeEach((to, from, next) => {
    console.log(to.meta);
    if (to.meta.signedIn) {
        const routerUser = {
            user_id: window.localStorage.user_id, 
            api_token: window.localStorage.api_token
        };
        console.log(routerUser);
        if(routerUser.user_id && routerUser.api_token) {
            next();
        } else {
            next(false);
        }
    } else {
        const routerUser = {
            user_id: window.localStorage.user_id, 
            api_token: window.localStorage.api_token
        };
        if(routerUser.user_id && routerUser.api_token) {
            if(to.path == '/search/:query') {
                next();
            } else {
                next(false);
            }
        } else {
            next();
        }
    }
});

export default router;