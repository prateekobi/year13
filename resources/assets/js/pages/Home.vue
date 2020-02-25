<template>
  <div class="container py-3">
    <div class="row">
      <div class="col-12 text-center">
        <div class="form">
          <div class="form-group row">
            <div class="col-md-5">
              <label>Occupation 1</label>
              <select-occupation v-model="occupation_1"></select-occupation>
            </div>
            <div class="col-md-5">
              <label>Occupation 2</label>
              <select-occupation v-model="occupation_2"></select-occupation>
            </div>
            <div class="col-md-2">
              <button
                class="btn btn-danger btn-block mt-4"
                @click.prevent="compare"
                :disabled="!occupation_1 || !occupation_2 || loading"
              >
                <template v-if="loading">
                  <i class="fa fa-refresh fa-spin"></i>
                </template>
                <template v-else>Compare</template>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <template v-if="match && !loading">
        <div class="col-12 text-center">
          <h1 :style="`color: ${color()}`">{{ match }}% Match</h1>
        </div>
        <h2>Similarities</h2>
        <table class="table">
          <thead class="thead-dark">
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">value</th>
          </thead>
          <tbody>
            <tr v-for="occ in simalarity" :key="occ.title">
              <th>{{ occ.label }}</th>
              <th>{{ occ.description }}</th>
              <th>{{ occ.value }}</th>
            </tr>
          </tbody>
        </table>
      </template>
      <template v-else-if="!match && !loading">
        <div class="col-12 text-center">Please select two Occupations from above and click Compare</div>
      </template>
      <template v-else-if="loading">
        <div class="col-12 text-center">Please wait...</div>
      </template>
    </div>
  </div>
</template>

<script>
import SelectOccupation from "../components/form-controls/SelectOccupation";
export default {
  name: "home-page",
  components: {
    SelectOccupation
  },
  data() {
    return {
      loading: false,
      occupation_1: null,
      occupation_2: null,
      match: null,
      simalarity: null
    };
  },
  methods: {
    color() {
      return this.match >= 50 ? "#006400" : "#DAA520";
    },
    compare() {
      this.loading = true;
      this.axios
        .post("/api/compare", {
          occupation_1: this.occupation_1,
          occupation_2: this.occupation_2
        })
        .then(response => {
          this.loading = false;
          this.match = response.data.match;
          this.simalarity = response.data.simalarity;
        })
        .catch(() => {
          this.loading = false;
        });
    }
  }
};
</script>

<style lang="scss" scoped>
.form-group {
  label {
    font-size: 0.8rem;
    text-align: left;
    display: block;
    margin-bottom: 0.2rem;
  }
}
</style>