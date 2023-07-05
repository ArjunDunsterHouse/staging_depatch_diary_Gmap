import { defineStore } from 'pinia'
import axios from "axios";

export const navbar = defineStore(
      {
            id:  'navbar',

      state: () => ({

            weekNumber: 0,
      }),

      getters:{
            getWeek(state){
                  const currentDate = new Date();
                  const startDate = new Date(currentDate.getFullYear(), 0, 1);
                  const days = Math.floor((currentDate - startDate) /(24 * 60 * 60 * 1000));
                  state.weekNumber = Math.ceil(days / 7);
                  return state.weekNumber;
            }

      },
      

     // persist:true
  
})
