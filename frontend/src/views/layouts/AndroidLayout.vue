<template>	
  <div>
		test {{ Android.Message("test") }}
	</div>
	
</template>
<script>
  export default {
    name: "AndroidLayout",		
		computed: {
			isandroid() {
				return (typeof Android !== "undefined");
			},
		}
  };
</script>