<template>
    <div class="container">
        <form @submit.prevent="saveBookmark">
            <button type="submit" class="btn btn-primary">Отправить</button>
            <div class="form-group">
                <label for="tags">Теги</label>
                <multiselect autofocus v-model="selectedLabels" :options="labels" :multiple="true" :close-on-select="false" :clear-on-select="true" :taggable="true" :hideSelected="true" @tag="addTag" :showLabels="false" placeholder="" :limit="10" :options-limit="100"></multiselect>
            </div>
            <div class="form-group">
                <label for="title">Заголовок</label>
                <input type="text" class="form-control" id="title" aria-describedby="titleHelp" v-model="title">
            </div>
            <div class="form-group">
                <label for="url">Ссылка</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend" v-if="existedBookmark">
                        <span class="input-group-text" id="basic-addon1">V</span>
                    </div>
                    <input type="text" class="form-control" id="url" v-model="url">
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Описание</label>
                <input type="text" class="form-control" id="description" v-model="description">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="ishiddencheck">
                <label class="form-check-label" for="ishiddencheck">Скрыть ссылку</label>
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>

</template>
<script>
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import getCookie from '../helpers/getCookie'
import setCookie from '../helpers/setCookie'

export default {
    components: { Multiselect },
    data() {
        return {
            labels: ['options', 'selected', 'mulitple'],
            title: '',
            url: '',
            description: '',
            selectedLabels: [],
            existedBookmark: false,
            token: ''
        }
    },
    methods: {
        addTag (newTag) {
            this.selectedLabels.push(newTag.toLowerCase())
        },
        saveBookmark() {
            axios.post('/api/bookmarks',
            {
                title: this.title,
                url: this.url,
                description: this.description,
                labels: this.selectedLabels
            })
            .then(response => {
                console.log(response)    
                window.close()
            })
        },
        searchForExisted(url) {
            axios.get('api/url?q=' + url).then(response => {
                if(response.data) {
                    this.existedBookmark = true
                    this.title = response.data.title
                    this.description = response.data.description
                    response.data.labels.forEach((label) => {
                        this.selectedLabels.push(label.title)
                    })
                }
                
            })
        },
        getCookie,
        setCookie
    },

    mounted() {
        document.onreadystatechange = () => { 
            if (document.readyState == "complete") { 
                if(this.$route.query.title) this.title = this.$route.query.title
                if(this.$route.query.text) this.url = this.$route.query.text.replace(/(\&|\?)utm([+-=%_a-zA-Z0-9]+)/g, "")
                
                //ищем существующую закладку по сылке
                if( this.url) this.searchForExisted(this.url)
            } 
        }

        this.token = this.getCookie('jwt')
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`

        axios.interceptors.response.use(response => {
            return response
        }, error => {
            if (401 === error.response.status) {
                this.token = ''
                this.setCookie('jwt', '', 0)
                window.location = "/login?redirect="+ encodeURIComponent(this.$route.fullPath)
            } else {
                return Promise.reject(error)
            }
        })

        axios.post('/api/auth/me')
        .then(response => {
            // console.log(response)
            if(response && response.data && response.data.id) {
                console.log(response.data)
            }
        })
        .catch(error => {
            console.error(error)
        })

        if(this.token === '') window.location = "/login?redirect="+ encodeURIComponent(this.$route.fullPath)
        

        axios.get('/api/labels').then(response => {
            this.labels = response.data
        })
    }
}
</script>
<style>
@import '../../../node_modules/vue-multiselect/dist/vue-multiselect.min.css';

.multiselect__tag {
    background: #fff;
    color: #000;
}

.multiselect__tags {
    background: #000;
    border: 1px solid #6c757d;
}

.multiselect__option--highlight {
    background: #000;
    outline: none;
    color: #fff;
}
</style>