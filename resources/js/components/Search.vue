<template>
        <div class="container">
            <div class="row justify-content-center" ref="searchResults">
                <h2>Поиск по слову {{ $route.params.search }}</h2>
                <BookmarksList :bookmarks="bookmarks"/> 
            </div>
            <infinite-loading @infinite="infiniteHandler">
                <div slot="no-more"><!--Всё--></div>
            </infinite-loading>
        </div>
</template>

<script>
        import axios from 'axios';
        import InfiniteLoading from 'vue-infinite-loading'
        import BookmarksList from './BookmarksList.vue'
        import getCookie from '../helpers/getCookie'

        // import infiniteHandler from '../helpers/InfiniteHandler.js'
        //read https://stackoverflow.com/questions/49144933/vuejs-accessing-externaly-imported-method-in-vue-component
        
        export default {
            data(){
                return {
                  page: 1,
                  bookmarks: [],
                  token: ''
                }
            },
            methods: {
                infiniteHandler($state) {
                    // console.log('/api/bookmarks/' + this.$route.params.search, this.page)
                    axios.get('/api/bookmarks/' + this.$route.params.search, {
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
            components: {
                InfiniteLoading,
                BookmarksList
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
.highlight {
  background-color: yellow;
}
</style>