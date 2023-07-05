import { defineStore } from 'pinia'


export const sidebar = defineStore(
{
      id:  'sidebar',

      state: () => ({

            is_expanded:false,
      }),

      //persist:true
  
})
