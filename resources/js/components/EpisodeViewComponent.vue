<template>
    <div class="container">
        <div class="input-group input-group-lg">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            <input type="text" class="form-control" v-model="query" />
        </div>

        <br />
        <div class="text-center" v-if="loading">
            <div class="bg-spinner">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="row">
            <episode-component
                :dataset="item"
                v-for="item in episodes"
                v-bind:key="item.id"
            ></episode-component>
        </div>

        <pagination
            :data="data"
            align="center"
            v-on:pagination-change-page="changePage"
        ></pagination>
    </div>
</template>

<style lang="scss">
h5 {
    font-weight: lighter;
    padding: 12px 0;
}

.bg-spinner {
    padding: 24px 0;
}
</style>

<script>
export default {
    mounted() {
        this.getData();
    },
    created: function() {
        // define debounceRefresh function.
        // necessary for a later call if a search parameter is available.
        this.debouncedRefresh = _.debounce(this.getData, 800);

        // after creation, check whether any search parameter is available.
        var url = new URL(window.location.href);
        if (url.searchParams.has("search")) {
            this.query = url.searchParams.get("search");
        }
    },
    props: {
        url: String
    },
    watch: {
        query: function() {
            this.episodes = [];
            this.loading = true;
            this.debouncedRefresh();

            // Change url while typing
            var url = new URL(window.location.href);
            url.searchParams.delete("page");
            if (this.query) {
                url.searchParams.set("search", this.query);
            } else {
                url.searchParams.delete("search");
            }
            window.history.replaceState("", "", url.href);
        }
    },
    data() {
        return {
            data: {},
            episodes: [],
            loading: false,
            query: null
        };
    },
    methods: {
        changePage(page = 1) {
            var url = new URL(window.location.href);
            url.searchParams.set("page", page);
            window.history.pushState("", "", url.href);
            window.scrollTo(0, 0);
            this.getData(page);
        },
        getData(page) {
            var _this = this;
            axios
                .get(window.location.href, {
                    params: {
                        search: this.query
                    }
                })
                .then(function(response) {
                    console.log(response);
                    _this.loading = false;
                    _this.data = response.data;
                    _this.episodes = response.data.data;
                });
        }
    }
};
</script>
