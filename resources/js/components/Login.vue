<template>
    <div class="container">
        <form @submit.prevent="login">
            <h3>Вход</h3>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control form-control-lg" v-model="email" />
            </div>

            <div class="form-group">
                <label>Пароль</label>
                <input type="password" class="form-control form-control-lg" v-model="password"/>
            </div>

            <button type="submit" class="btn btn-lg btn-primary">Войти</button>
        </form>
    </div>
</template>

<script>
import setCookie from '../helpers/setCookie'

    export default {
        data() {
            return {
                email: '',
                password: ''
            }
        },
        methods: {
            login() {
                axios.post('/api/auth/login',
                {
                    "email": this.email,
                    "password": this.password
                })
                .then(user => {
                    console.log(user)
                    if (user.data.access_token) {
                        localStorage.setItem('user', JSON.stringify(user.data))
                        this.setCookie('jwt', user.data.access_token, 300)
                        if(this.$route.query.redirect) window.location = decodeURIComponent(this.$route.query.redirect) //this.$router.push(this.$route.query.redirect)
                        // else this.$router.push("/")
                        window.location = "/"
                    }
                });
            },
            setCookie
        }
    }
</script>