const formRules = {
   required: (v: any) => !!v || "This field is required.",
   minimum: (min: number) => {
      return (v: string) => !v || (v.trim() || "").length >= min || `Minimum ${min} characters.`;
   },
   maximum: (max: number) => {
      return (v: string) => !v || (v.trim() || "").length <= max || `Maximum ${max} characters.`;
   },
   numeric: (v: string) => {
      if (v) return /^\d+$/.test(v) || "This field must contain only numbers.";
      else return true;
   },
   alphaSpace: (v: string) => {
      if (v) return /^[a-zA-Z ]*$/.test(v) || "This field may only contain alphabetical characters and spaces.";
      else return true;
   },
   validEmail: (v: string) => {
      if (v) return /.+@.+\..+/.test(v) || "E-mail must be valid format";
      else return true;
   },
   validMobileNumber: (v: string) => {
      if (v) return /^(9)\d{9}$/.test(v) || "Please enter a valid mobile number.";
      else return true;
   },

   positiveNumber: (v: string) => {
      if (v) return /^[+]?\d+([.]\d+)?$/.test(v) || "Please enter a positive number.";
      return true;
   },
};

export default formRules;
