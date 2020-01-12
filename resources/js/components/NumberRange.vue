<template>
  <div class="input-group">
    <div class="input-group-prepend">
      <button class="btn btn-danger" type="button" v-on:click="decrease">
        <i class="fas fa-minus"></i>
      </button>
    </div>
    <input type="number" class="form-control text-center" v-model="number" :name="name" :id="id" />
    <div class="input-group-append">
      <button class="btn btn-danger" type="button" v-on:click="increase">
        <i class="fas fa-plus"></i>
      </button>
    </div>
  </div>
</template>

<style lang="scss">
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type="number"] {
  -moz-appearance: textfield;
}
</style>

<script>
export default {
  props: {
    id: {
      type: String
    },
    name: {
      type: String
    },
    default: {
      type: Number,
      required: false
    }
  },
  data() {
    return {
      number: 0
    };
  },
  created() {
    if (this.default) {
      this.number = this.default;
    }
  },
  methods: {
    increase() {
      this.number++;
    },
    decrease() {
      this.number--;
    }
  }
};
</script>
