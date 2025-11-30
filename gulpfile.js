import { src, dest, watch, series } from 'gulp'
import * as dartSass from 'sass'
import gulpSass from 'gulp-sass'
import terser from 'gulp-terser'
import plumber from 'gulp-plumber'

const sass = gulpSass(dartSass)

const paths = {
  scss: 'src/scss/**/*.scss',
  js: 'src/js/**/*.js'
}

// --- Manejo de errores genérico ---
function handleError(err) {
  console.error('❌ Error en la tarea:', err.message);
  this.emit('end'); // evita que Gulp se detenga
}

// --- Tarea CSS ---
export function css(done) {
  src(paths.scss, { sourcemaps: true })
    .pipe(plumber({ errorHandler: handleError }))
    .pipe(
      sass({
        outputStyle: 'compressed'
      }).on('error', sass.logError)
    )
    .pipe(dest('./public/build/css', { sourcemaps: '.' }));
  done();
}

// --- Tarea JS ---
export function js(done) {
  src(paths.js)
    .pipe(plumber({ errorHandler: handleError }))
    .pipe(terser())
    .pipe(dest('./public/build/js'));
  done();
}

// --- Tarea de desarrollo ---
export function dev() {
  watch(paths.scss, css);
  watch(paths.js, js);
}

export default series(js, css, dev);
