import { format, parseISO } from 'date-fns'
import { getWeekOfMonth } from 'date-fns'

const getOrdinalNum = (n: any) => n + (n > 0 ? ['th', 'st', 'nd', 'rd'][(n > 3 && n < 21) || n % 10 > 3 ? 0 : n % 10] : '')

const formatDate = (date: string, type = 'datetime', customFormat = '') => {
   if (date) {
      if (type == 'datetime') {
         return format(new Date(date), 'MMM dd, yyyy hh:mm a')
      }

      if (type == 'date') {
         return format(new Date(date), 'MMM dd, yyyy')
      }

      if (type == 'monthweek') {
         return format(new Date(date), 'MMM, yyyy') + ' (' + getOrdinalNum(getWeekOfMonth(new Date(date), { weekStartsOn: 2 })) + ' week)'
      }

      if (type == 'monthyear') {
         return format(new Date(date), 'MMM, yyyy')
      }

      if (type == 'format') {
         return format(new Date(date), customFormat)
      }

      if (type == 'monthDay') {
         return format(new Date(date), 'MMM dd')
      }

      if (type == 'timeOnly') {
         return format(new Date(date), 'hh:mm a')
      }

      if (type == 'custom_date') {
         return format(parseISO(date), 'MMM dd, yy')
      }

      if (type == 'Shortdate') {
         return format(new Date(date), 'MM/dd/yyyy')
      }

   } else {
      return '-'
   }
}

export default formatDate