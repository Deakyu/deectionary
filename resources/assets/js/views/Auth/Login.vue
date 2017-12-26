<template>
<div>
    <img src="/images/Double_Ring.gif" class="loader" v-show="isProcessing">
    <form @submit.prevent="login" class="form" v-show="!isProcessing">
        <h1 class="form__title">Login</h1>
        <div class="form__group">
            <input :class="{err: error.email}" type="text" class="form__control" placeholder="email" v-model="form.email">
            <small class="error__control" v-if="error.email">{{error.email[0]}}</small>
        </div>

        <div class="form__group">
            <input :class="{err: error.password}" type="password" class="form__control" placeholder="password" v-model="form.password">
            <small class="error__control" v-if="error.password">{{error.password[0]}}</small>
        </div>

        <router-link class="form__signup-link" to="/register" tag="small">
            <a>Not Signed up yet? Sign up here!</a>
        </router-link>        

        <div class="form__group">
            <button class="btn btn--primary" :disabled="isProcessing">Login</button>
        </div>
    </form>
</div>
</template>

<script>
    import Flash from '../../helpers/flash';
    import Auth from '../../helpers/auth';
    import { post } from '../../helpers/api';

    export default {
        data() {
            return {
                form: {
                    email: "",
                    password: ""
                },
                error: {},
                isProcessing: false,
            };
        },
        methods: {
            login() {
                this.isProcessing = true;
                this.error = {};
                post('/api/login', this.form)
                .then((res) => {
                    if(res.data.authenticated) {
                        Auth.set(res.data.api_token, res.data.user_id, res.data.email.substring(0, res.data.email.lastIndexOf("@")));
                        Flash.setSuccess("You have successfully logged in!");
                        this.form.email ="";
                        this.form.password="";
                        this.$router.push({path: "/search/welcome"});
                        this.isProcessing = false;
                    }
                })
                .catch((err) => {
                    if(err.response.status == 422) {
                        this.error = err.response.data;
                        Flash.setError("Wrong credentials, please try again");
                        this.form.password = "";
                        this.isProcessing = false;
                    }
                });
            }
        },
    }
</script>