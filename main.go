package main

import (
	"html/template"
	"net/http"
)

// Define a struct to hold the data you want to send to the HTML template
type FormData struct {
	Name  string
	Email string
}

func main() {
	http.HandleFunc("/login", serveForm)
	http.HandleFunc("/submit", submitForm)
	http.HandleFunc("/register", serveRegisterForm)
	http.Handle("/styles/", http.StripPrefix("/styles/", http.FileServer(http.Dir("styles"))))
	http.ListenAndServe(":8080", nil)
}

func serveForm(w http.ResponseWriter, r *http.Request) {
	http.ServeFile(w, r, "login.html")
}

func serveRegisterForm(w http.ResponseWriter, r *http.Request) {
	http.ServeFile(w, r, "register.html")
}

func submitForm(w http.ResponseWriter, r *http.Request) {
	if r.Method != "POST" {
		http.Error(w, "Method not allowed", http.StatusMethodNotAllowed)
		return
	}

	err := r.ParseForm()
	if err != nil {
		http.Error(w, "Error parsing form", http.StatusBadRequest)
		return
	}

	name := r.Form.Get("name")
	email := r.Form.Get("email")

	formData := FormData{Name: name, Email: email}

	tmpl := template.Must(template.ParseFiles("submit.html"))
	err = tmpl.Execute(w, formData)
	if err != nil {
		http.Error(w, "Error rendering template", http.StatusInternalServerError)
		return
	}
}
