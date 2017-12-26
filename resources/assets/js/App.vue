<template>
<div>
    <nav class="nav">
        <ul class="nav__navbar">
            <router-link class="nav__brand" tag="li" :to="check ? '/search/welcome' : '/'" exact>
                <a>Deectionary</a>
            </router-link>

            <form class="top-search" @submit.prevent="search" v-if="check">
                <input type="text" class="top-search__input" placeholder="Search here!" v-model="query">
                <button class="top-search__button">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </form>

            <button class="nav__side-btn" :class="{toggle: isMenuOpen}" @click="toggleMenu">
                <span class="nav__hamburger"></span>
            </button>

            <div class="nav__sidebar" @click="toggleMenu">
                <ul>
                    <li class="nav__side-list" v-if="check">
                        <a><i class="fa fa-user-o"></i>&nbsp;&nbsp;{{auth.email}}</a>
                    </li>
                    <router-link to="/" tag="li" class="nav__side-list" v-if="!check">
                        <a><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;&nbsp;Sign In</a>
                    </router-link>
                    <router-link to="/register" tag="li" class="nav__side-list" v-if="!check">
                        <a><i class="fa fa-user-circle-o" aria-hidden="true"></i>&nbsp;&nbsp;Sign Up</a>
                    </router-link>
                    <router-link to="/marked" tag="li" class="nav__side-list" v-if="check">
                        <a><i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;&nbsp;Bookmark</a>
                    </router-link>
                    <router-link to="/examples" tag="li" class="nav__side-list" v-if="check">
                        <a><i class="fa fa-pencil-square" aria-hidden="true"></i>&nbsp;&nbsp;Examples</a>
                    </router-link>
                    <li class="nav__side-list" v-if="check">
                        <a @click.stop="logout"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;&nbsp;Logout</a>
                    </li>
                </ul>
            </div>
        </ul>
    </nav>
    <div class="top-push">
        &nbsp;
    </div>
    <div class="flash flash--success" :class="{show: flash.success}">
        {{flash.success}}
    </div>
    <div class="flash flash--error" :class="{show: flash.error}">
        {{flash.error}}
    </div>
    <button class="close-menu" @click="closeMenu" v-show="isMenuOpen"></button>
    <router-view></router-view>
</div>
    
</template>

<script>
    import Flash from './helpers/flash';
    import Auth from './helpers/auth';
    import { post } from './helpers/api';

    export default {
        created() {
            Auth.initialize();
        },
        data() {
            return {
                flash: Flash.state,
                auth: Auth.state,
                isMenuOpen: false,
                query: ""
            };
        },
        computed: {
            check() {
                return this.auth.api_token && this.auth.user_id ? true : false;
            }
        },
        methods: {
            toggleMenu() {
                if(this.isMenuOpen) {
                    $('.nav__sidebar').animate({width: 'hide'}, 200);
                } else {
                    $('.nav__sidebar').animate({width: 'show'}, 200);
                }
                this.isMenuOpen = !this.isMenuOpen;
            },
            closeMenu() {
                if(this.isMenuOpen) {
                    $('.nav__sidebar').animate({width: 'hide'}, 200);
                    this.isMenuOpen = false;
                }
            },
            logout() {
                post('/api/logout')
                .then((res) => {
                    if(res.data.logged_out) {
                        Auth.remove();
                        Flash.setSuccess("You have successfully logged out!");
                        this.$router.push({path: "/"});
                    }
                })
                .catch((err) => {

                });
            },
            search() {
                const param = this.query;
                this.query = "";
                this.$router.push({path: '/search/' + param});
            }
        },
        mounted() {
            console.log("App Mounted!");
        }
    }
</script>