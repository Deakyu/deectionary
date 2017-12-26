<template>
<div>
    <img class="loader" src="/images/Double_Ring.gif" v-show="isProcessing">
    <form class="form" @submit.prevent="register" v-show="!isProcessing">
        <h1 class="form__title">Create An Account</h1>

        <div class="form__group">
            <input class="form__control" :class="{err: error.email}" type="text" placeholder="email" v-model="form.email">
            <small class="error__control" v-if="error.email">{{error.email[0]}}</small>
        </div>

        <div class="form__group">
            <input class="form__control" :class="{err: error.password}" type="password" placeholder="password" v-model="form.password">
            <small class="error__control" v-if="error.password">{{error.password[0]}}</small>
        </div>

        <div class="form__group">
            <input class="form__control" :class="{err: !form.password_match}" type="password" placeholder="confirm password" v-model="form.password_confirmation" @keyup="matchCheck">
            <small class="error__control" v-if="!form.password_match">Passwords don't match!</small>
        </div>

        <div class="form__group">
            <button class="btn btn--primary" :disabled="(isProcessing || !form.password_match)">Register</button>
        </div>
    </form>
</div>
    
</template>

<script>
    import Flash from '../../helpers/flash';
    import { post } from '../../helpers/api';

    export default {
        data() {
            return {
                form: {
                    email: "",
                    username: "",
                    password: "",
                    password_confirmation:"",
                    password_match: true,
                },
                error: {},
                isProcessing: false,
            };
        },
        methods: {
            matchCheck() {
                this.form.password_match =  this.form.password == this.form.password_confirmation;
            },
            register() {
                this.error = {};
                this.isProcessing = true;
                let splitedEmail = this.form.email.split('@');
                this.form.username = splitedEmail[0];
                post('/api/register', this.form)
                .then((res) => {
                    if(res.data.registered) {
                        Flash.setSuccess('Please verify your email to activate your account');
                        this.$router.push({path: '/emailcheck'});
                        this.isProcessing = false;
                    }
                })
                .catch((err) => {
                    if(err.response.status == 422) {
                        this.error = err.response.data;
                        Flash.setError("Oops! something went wrong registering you");
                        this.form.password = "";
                        this.form.password_confirmation = "";
                        this.isProcessing = false;
                    }
                });
            }
        },
        mounted() {
            
        }
    }
</script>