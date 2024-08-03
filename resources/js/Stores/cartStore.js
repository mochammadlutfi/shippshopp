import { defineStore } from 'pinia'

export const useCartStore = defineStore('cartStore', {
    
    state: () => ({
        items : [],
    }),
    getters: {
        count: (state) => state.items.length,
    },
    actions: {
        async fetchCart() {
            try {
                const response = await axios.get("/cart/data",{
                });
                if(response.status == 200){
                    this.items = response.data;
                }
            } catch (error) {
                console.error(error);
            }
      },
    }
})