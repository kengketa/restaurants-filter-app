<template>
  <div class="w-full bg-gray-100 p-4 min-h-screen">
    <div class="w-full lg:w-1/2">
      <div>
        <label class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white"
               for="default-search">Search</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg aria-hidden="true" class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" stroke="currentColor" stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"/>
            </svg>
          </div>
          <input id="default-search" ref="searchRef"
                 v-model="filters.search"
                 class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                 placeholder="ค้นหา" type="search">
          <button
              class="text-white absolute right-2.5 bottom-2.5 bg-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 transition-all ease-in-out duration-200"
              type="button" @click="clearSearch">
            Clear
          </button>
        </div>
      </div>
    </div>
    <div class="flex flex-col items-center mt-6">
      <div v-if="loaded" class="grid gap-2 md:gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <div v-for="(restaurant,index) in restaurants" :key="index"
             class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 transition-all ease-in-out duration-300 hover:scale-105">
          <a href="#">
            <img :src="restaurant.photo ?? '/images/fallback.jpeg'" alt=""
                 class="rounded-t-lg object-cover w-full h-60 md:h-40 lg:h-60"/>
          </a>
          <div class="p-5">
            <a href="#">
              <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ restaurant.name }}
              </h5>
            </a>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ restaurant.address }}</p>
          </div>
        </div>
      </div>
      <div v-if="!loaded" class="mt-20">
        <div role="status">
          <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
               fill="none" viewBox="0 0 100 101" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                fill="currentColor"/>
            <path
                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                fill="currentFill"/>
          </svg>
          <span class="sr-only">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: 'RestaurantIndex',
  components: {},
  props: {},
  data() {
    return {
      filters: {
        search: 'Bang sue'
      },
      restaurants: [],
      debounce: null,
      loaded: false,
    };
  },
  async mounted() {
    this.$refs.searchRef.focus();
    await this.fetchRestaurants();

  },
  methods: {
    fetchRestaurants() {
      if (!this.filters.search) {
        return;
      }
      clearTimeout(this.debounce)
      this.debounce = setTimeout(async () => {
        this.loaded = false;
        const url = route('api.restaurants.fetch');
        const res = await axios.get(url, {
          params: this.filters
        });
        this.restaurants = res.data;
        this.loaded = true;
      }, 1000);
    },
    clearSearch() {
      this.filters.search = 'Bang sue';
    }
  },
  watch: {
    filters: {
      deep: true,
      handler() {
        this.fetchRestaurants();
      }
    }
  }
};
</script>
