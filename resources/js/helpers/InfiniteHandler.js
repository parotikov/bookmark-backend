import axios from 'axios'

export default function (url, page, entity, $state) {
    axios.get(url, {
            params: {
                page: page,
            }
        },
    ).then(({
    data
}) => {
    console.log(data.data)
    if (data.data.length) {
        page += 1
        entity.push(...data.data)
        $state.loaded()
    } else {
        $state.complete()
    }
})
}