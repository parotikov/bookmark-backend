<template>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8" >
                    <div class="my-4">
                        <h6 v-for="label,index in labels">
                            <a :href="'/labels/' + label">{{label}}</a>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import axios from 'axios'
import getCookie from '../helpers/getCookie'

export default {
    data(){
        return {
            labels: [],
            token: ''
        }
    },
    methods: {
        getCookie
    },
    mounted() {
        this.token = this.getCookie('jwt')
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`

        axios.get('/api/labels').then(response => {
            console.log(response)
            this.labels = response.data
            // response.data.forEach((label) => {
            //     this.labels.push(label)
            // })
            
        })
    }
}
</script>
<style>
.h6, h6 {
    display: inline-block;
    padding-right:4px;
}
</style>