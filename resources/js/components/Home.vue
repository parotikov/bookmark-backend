<template>
        <div class="container">
            <div class="row justify-content-center">
                <h2>Последние ссылки</h2>
                <BookmarksList :bookmarks="bookmarks"/> 
            </div>
            <infinite-loading @infinite="infiniteHandler"></infinite-loading>
        </div>
</template>

<script>
        import axios from 'axios'
        import InfiniteLoading from 'vue-infinite-loading'
        import BookmarksList from './BookmarksList.vue'
        import getCookie from '../helpers/getCookie'

        export default {
            data(){
                return {
                  bookmarks: [],
                  page: 1,
                  token: ''
                }
            },
            components: {
                InfiniteLoading,
                BookmarksList
            },
            methods: {
                infiniteHandler($state) {
                    console.log($state)
                    axios.get('/api/bookmarks', {
                        params: {
                            page: this.page,
                        },
                    }).then(({ data }) => {
                        console.log(data.data)
                        if (data.data.length) {
                            this.page += 1
                            this.bookmarks.push(...data.data)
                            $state.loaded()
                        } else {
                            $state.complete()
                        }
                    });
                },
                getCookie
            },
            mounted() {
                this.token = this.getCookie('jwt')
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            }
        }
</script>
<style>
.h6, h6 {
    display: inline-block;
    padding-right:4px;
}
</style>