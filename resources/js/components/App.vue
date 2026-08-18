<template>
        <div>
            <nav class="navbar navbar-expand-md sticky-top navbar-dark bg-dark" id="navbar">
                <div class="container">
                    <router-link :to="{name: 'home'}" class="navbar-brand">PB</router-link>
                    <form class="form-inline" @submit.prevent="doSearch">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" v-model="search">
                    </form>
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <li>
                                <router-link :to="{name: 'home'}" class="nav-link">Bookmarks</router-link>
                            </li>
                            <li>
                                <router-link :to="{name: 'labels'}" class="nav-link">Labels</router-link>
                            </li>
                            <li v-if="token">
                                <router-link :to="{name: 'add'}" class="nav-link">Add new</router-link>
                            </li>
                            <li v-else>
                                <router-link :to="{name: 'login'}" class="nav-link">Login</router-link>
                            </li>
                            <li v-if="token">
                                <a href="#" class="nav-link" @click.prevent="logOut">Logout</a>
                            </li>
                            <!--<li>
                                <a href="#" class="nav-link" @click.prevent="darkThemeSwitch">Switch</a>
                            </li>-->
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="py-4">
                <router-view></router-view>
            </div>
        </div>
</template>
<script>
import getCookie from '../helpers/getCookie'
import setCookie from '../helpers/setCookie'

export default {
    data() {
        return {
            search: '',
            token: ''
            // mode: ''
        }
    },
    // watch: {
    //     mode: function (val) {
    //         this.darkThemeSwitch()
    //     },
    // },
    methods: {
        doSearch () {
            window.location.href = '/bookmarks/' + this.search
        },
        getCookie,
        setCookie,
        logOut() {
            axios.post('/api/auth/me')
            .then(response => {
                console.log(response)
                if(response && response.data) {
                    console.log(response.data)
                    this.setCookie('jwt', '', 0)
                    window.location = "/"
                }
            })
        }
        
    },
    computed: {
        isLogged() {
            return this.user && this.user.access_token
        }
    },
    mounted() {
        this.token = this.getCookie('jwt')
        const segments = this.$route.path.split('/')
        if(this.$route.name == 'search') this.search = decodeURI(segments[segments.length - 1])
    }
}
</script>
<style>
@import '/node_modules/@forevolve/bootstrap-dark/dist/css/bootstrap-dark.css';
</style>