<template>
  <div id="quran" class="card border-success mb-3 ml-auto mr-auto mt-4">
    <div class="card-body text-white bg-dark text-right">
      <p class="card-text" v-for="verse in verses" :key="verse.id">
        {{ verse.text }}
      </p>
    </div>
    <div class="card-footer border-success text-white bg-dark">
      <span class="badge badge-light">{{ page }}</span>
      <span v-if="start_surah" class="badge badge-light">{{ start_surah }}</span>
      <span v-if="start_surah != end_surah" class="badge badge-light">{{ end_surah }}</span>
      <div class="float-right">
        <nav>
          <ul class="pagination justify-content-end">
            <li class="page-item">
              <a
                class="page-link"
                href="#"
                v-on:click="previous()"
                tabindex="-1"
                >السابق</a
              >
            </li>
            <li class="page-item">
              <a class="page-link" v-on:click="next()" href="#">التالي</a>
            </li>
            <li class="page-item" v-if="pageAsPng !== null">
              <a class="page-link" :href="pageAsPng" target="_blank">تحميل</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      page: 1,
      verses: [],
      pageAsPng: null,
      start_surah: null,
      end_surah: null,
    };
  },
  mounted() {
    this.goToPage(1);
  },
  watch: {
    page: function (val) {
      if (val !== 0) this.goToPage(val);
    },
  },
  methods: {
    next(num = null) {
      this.page = num ? num : this.page + 1;
    },
    previous(num = null) {
      if (this.page > 1) this.page = num ? num : this.page - 1;
    },
    goToPage(page) {
      axios.get("/api/page/" + page).then((response) => {
        var data = response.data;
        this.verses = data.verses;
        this.pageAsPng = data.page.image;
        this.start_surah = data.page.start_surah.name_ar;
        this.end_surah = data.page.end_surah.name_ar;
      });
    },
  },
};
</script>
