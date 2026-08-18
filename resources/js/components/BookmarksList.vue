<template>
    <div class="row justify-content-center">
    <div class="col-md-10" v-for="bookmark,index in bookmarks" :key="bookmark.id">
        <div class="my-4">
            <h5>
                <a :href="bookmark.url" :class="{invalid : !bookmark.is_valid}" target="_blank">{{bookmark.title}}</a>
                <!--<router-link :to="{ name: 'bookmark', params: {bookmarkId: bookmark.id}}">
                    {{bookmark.title}}
                </router-link>-->
            </h5>
            <h6 v-for="label,index in bookmark.labels" :key="label.id">
                <a :href="'/labels/' + label.title">{{label.title}}</a><span v-if="index < bookmark.labels.length - 1">,</span>
                <!--<router-link :to="{ name: 'label', params: {labelId: label.id}}">
                    {{label.title}}
                </router-link>
                -->
            </h6>  
            <small class="text-muted">{{formateDate(bookmark.added_at)}}</small>
            <div v-if="bookmark.description">
            <small class="text-muted">{{bookmark.description}}</small>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
import moment from 'moment'
moment.locale('ru')

export default {
    name: 'BookmarksList',
    props: {
        bookmarks: {
            type: Array,
            default: [],
        }
    },
    methods: {
        formateDate(date) {
            return moment(date).format('DD.MM.YY hh:mm') + ' (' + moment(date).fromNow() + ')'
        }
    }
}
</script>
<style>
.h6, h6 {
    display: inline-block;
    padding-right:4px;
}

.invalid {
    color: red
}
</style>