<template>
  <div class="col-md-4" style="padding: 12px;">
    <div class="card">
      <div class="cards-body">
        <div class="episode">
          <button type="button" class="btn btn-episode" v-on:click="decrease">
            <i class="fas fa-minus"></i>
          </button>
          <div class="episode-body">
            <div v-if="editMode">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">Title</span>
                </div>
                <input type="text" class="form-control" v-model="name" />
              </div>
            </div>
            <div v-else>
              <h5 class="episode-title">{{ name }}</h5>
            </div>
            <div class="episode-season" v-on:click="changeCursor(0)">
              <div v-if="editMode">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Season</span>
                  </div>
                  <input type="text" class="form-control" v-model="season" />
                </div>
              </div>
              <div v-else>
                <p v-bind:class="{ 'episode-focus': cursor == 0 }">Season {{ season }}</p>
              </div>
            </div>
            <div class="episode-count" v-on:click="changeCursor(1)">
              <div v-if="editMode">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Episode</span>
                  </div>
                  <input type="text" class="form-control" v-model="episode" />
                </div>
              </div>
              <div v-else>
                <p v-bind:class="{ 'episode-focus': cursor == 1 }">Episode {{ episode }}</p>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-episode" v-on:click="increase">
            <i class="fas fa-plus"></i>
          </button>
        </div>
        <div class="card-footer text-muted">
          <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-light" v-on:click="toggleEditMode">
              <div v-if="!editMode">
                <i class="fas fa-pen"></i>
              </div>
              <div v-else>
                <i class="fas fa-save"></i>
              </div>
            </button>
            <button
              type="button"
              class="btn btn-light"
              v-if="editMode"
              v-on:click="destroyConfirmation"
            >
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.episode {
  display: flex;
  flex-direction: row;

  &:hover {
    .btn-episode {
      background-color: #da1b2a;
      color: #ffffff;
    }
  }
}

.episode-body {
  flex: 1;
  padding: 12px;
  text-align: center;

  .episode-title {
    color: lighten($color: #000000, $amount: 45%);
  }

  .episode-season,
  .episode-count {
    position: relative;
    font-size: 12pt;
    cursor: default;
  }

  p {
    padding: 6px;
  }
  p.episode-focus {
    padding: 6px;
    background-color: darken($color: #ffffff, $amount: 8%);
  }
}

.btn-episode {
  background-color: darken($color: #ffffff, $amount: 5%);
  color: #000000;

  &:first-child {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
  &:last-child {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
  }
}
</style>

<script>
/**
 * TODO: outsource ajax requests in EpisodeViewComponent
 * TODO: success, failure notification for edit and destroy needed
 * TODO: pass resource controller routes via meta tag
 */
export default {
  props: {
    dataset: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      cursor: 1,
      editMode: false,
      id: 0,
      name: "",
      season: 0,
      episode: 0
    };
  },
  created() {
    this.id = this.dataset.id;
    this.name = this.dataset.name;
    this.season = this.dataset.season;
    this.episode = this.dataset.episode;
    this.debounceUpdate = _.debounce(this.updateComponent, 1200);

    this.$watch("name", this.debounceUpdate);
    this.$watch("season", this.debounceUpdate);
    this.$watch("episode", this.debounceUpdate);
  },
  methods: {
    increase() {
      switch (this.cursor) {
        case 0:
          this.season++;
          break;
        case 1:
          this.episode++;
          break;
      }
    },
    decrease() {
      switch (this.cursor) {
        case 0:
          if (this.season > 0) this.season--;
          break;
        case 1:
          if (this.episode > 0) this.episode--;
          break;
      }
    },
    changeCursor(pos) {
      this.cursor = pos;
    },
    toggleEditMode() {
      this.editMode = !this.editMode;
    },
    updateComponent() {
      axios
        .patch("/storage/" + this.id, {
          _token: this.csrf,
          name: this.name,
          season: this.season,
          episode: this.episode
        })
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error.response.data);
        });
    },
    destroyConfirmation() {
      if (confirm("Are you really sure?")) {
        this.destroyComponent();
      }
    },
    destroyComponent() {
      axios
        .delete("/storage/" + this.id, {
          _token: this.csrf
        })
        .then(response => {
          console.log(response);
          location.reload();
        })
        .catch(error => {
          console.log(error.response.data);
        });
    }
  }
};
</script>
