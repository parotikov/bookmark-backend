<template>
        <div class="container">
            <div class="row justify-content-center" ref="searchResults">
            <h2>Редактирование тега {{ $route.params.label }}</h2>
                <div class="col-md-8">
                    <div class="my-4">
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
        import axios from 'axios';
        import getCookie from '../helpers/getCookie'

        export default {
            data(){
                return {
                  label: {}
                }
            },
            methods: {
                getCookie
            },
            mounted() {
                this.token = this.getCookie('jwt')
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`

                // axios.get('/api/labels/').then(response => {
                //     if(response.data) {
                //         this.existedBookmark = true
                //         this.title = response.data.title
                //         this.description = response.data.description
                //         response.data.labels.forEach((label) => {
                //             this.selectedLabels.push(label.title)
                //         })
                //     }
                    
                // })
            }
        }
</script>
<style>
.h6, h6 {
    display: inline-block;
    padding-right:4px;
}
.highlight {
  background-color: yellow;
}
</style>