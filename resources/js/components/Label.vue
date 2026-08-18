<template>
        <div class="container">
            <div class="row justify-content-center" ref="searchResults">
            <h2>Поиск по метке {{ $route.params.label }}</h2>
                <div class="col-md-8" v-for="bookmark,index in bookmarks" :key="bookmarks.id">
                    <div class="my-4">
                        <h5>
                            <a :href="bookmark.url">{{bookmark.title}}</a>
                            <!--<router-link :to="{ name: 'bookmark', params: {bookmarkId: bookmark.id}}">
                                {{bookmark.title}}
                            </router-link>-->
                        </h5>
                        <h6 v-for="label,index in bookmark.labels" :key="label.id">
                            <a :href="'/bookmarks/' + label.title">{{label.title}}</a>
                            <!--<router-link :to="{ name: 'label', params: {labelId: label.id}}">
                                {{label.title}}
                            </router-link>
                            -->
                        </h6>  
                        <small class="text-muted">Posted on: {{bookmark.updated_at}}</small>
                    </div>
                </div>
            </div>
            <infinite-loading @infinite="infiniteHandler"></infinite-loading>
        </div>
</template>

<script>
        import axios from 'axios';
        import InfiniteLoading from 'vue-infinite-loading'
        import getCookie from '../helpers/getCookie'

        export default {
            data(){
                return {
                  page: 1,
                  token: '',
                  bookmarks: []
                }
            },
            methods: {
                infiniteHandler($state) {
                    axios.get('/api/labels/' + this.$route.params.label, {
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