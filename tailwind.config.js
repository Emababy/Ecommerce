/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
            './admin/**/*index.php',
            './admin/**/*init.php',
            './admin/**/*dashboard.php',
            './admin/**/*members.php',
            './admin/**/*Items.php',
            './admin/**/*Comments.php',
            './admin/includes/templates/**/*footer.php',
            './admin/includes/templates/**/*header.php',
            './admin/includes/templates/**/*navbar.php',
            './admin/layout/css/**/*.css',
            './admin/layout/js/**/*.js',
            './includes/templates/**/*.php',
            './includes/templates/**/*header.php',
            './includes/templates/**/*footer.php',
            './**/*index.php',
            './**/*init.php',
            './**/*logout.php',
            './**/*Categories.php',
            './**/*login.php',
            './**/*SignUp.php',
            './**/*profile.php',
            './**/*InitLoginSignUp.php',
            './layout/js/**/*.js',
            './layout/css/**/*.css',
            './src/**/*style.css',
],
  theme: {
    extend: {
      fontSize:{
        '10px': "10px",
      },
      colors:{
        'Nike-red':"#e60000",
        'Nike-black':"#000000",
        'Nike-white':"#eee",
        'Nike-grey': "#767676",
        'Nike-orange':"#ff5733",
      }
    },
    fontFamily: {
      'poppins': ['Poppins', 'sans-serif']
    },
    container: {
      center: true,
      padding:"3rem",
    },
  },
}

