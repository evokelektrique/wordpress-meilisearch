module.exports = function (grunt) {
    grunt.initConfig({
        sass: {
            dist: {
                options: {
                    style: "compressed",
                    compass: false,
                    sourcemap: false,
                },
                files: {
                    "dist/styles/app.min.css": ["resources/styles/app.scss"],
                },
            },
        },
        cssmin: {
            target: {
                files: [
                    {
                        expand: true,
                        cwd: "resources/css",
                        src: ["*.css"],
                        dest: "dist/css",
                        ext: ".min.css",
                    },
                ],
            },
        },
        uglify: {
            target: {
                files: [
                    {
                        expand: true,
                        cwd: "resources/scripts",
                        src: ["*.js"],
                        dest: "dist/scripts",
                        ext: ".min.js",
                    },
                ],
            },
        },
        imagemin: {
            dynamic: {
                files: [
                    {
                        expand: true,
                        cwd: "resources/images",
                        src: ["**/*.{png,jpg,gif}"],
                        dest: "dist/images",
                    },
                ],
            },
        },
        watch: {
            sass: {
                files: ["resources/scss/**/*.scss"],
                tasks: ["sass", "cssmin"],
            },
            js: {
                files: ["resources/js/*.js"],
                tasks: ["uglify"],
            },
        },
    });

    grunt.loadNpmTasks("grunt-contrib-sass");
    grunt.loadNpmTasks("grunt-contrib-cssmin");
    grunt.loadNpmTasks("grunt-contrib-uglify");
    grunt.loadNpmTasks("grunt-contrib-watch");
    grunt.loadNpmTasks("grunt-contrib-imagemin");

    grunt.registerTask("default", [
        "sass",
        "cssmin",
        "uglify",
        "imagemin",
        "watch",
    ]);
};
