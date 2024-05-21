package main

import (
	"fmt"
	"html/template"
	"log"
	"net/http"
)

// Define a struct to hold the data you want to send to the HTML template
type FormData struct {
	FirstName    string
	LastName     string
	Email        string
	LicensePlate string
}

type Accommodation struct {
	Name     string
	Des      string
	Price    string
	ImageURL string
	Location string
}

func main() {
	fmt.Println("Starting server...")
	http.HandleFunc("/", mainForm)
	http.HandleFunc("/parken", parkenForm)
	http.HandleFunc("/contact", contactForm)
	http.HandleFunc("/login", serveForm)
	http.HandleFunc("/login-submit", submitForm)
	http.HandleFunc("/register-submit", submitForm)
	http.HandleFunc("/register", serveRegisterForm)
	http.Handle("/css/", http.StripPrefix("/css/", http.FileServer(http.Dir("css"))))
	http.Handle("/js/", http.StripPrefix("/js/", http.FileServer(http.Dir("js"))))
	http.Handle("/plugins/", http.StripPrefix("/plugins/", http.FileServer(http.Dir("plugins"))))
	http.Handle("/images/", http.StripPrefix("/images/", http.FileServer(http.Dir("images"))))
	http.Handle("/styles/", http.StripPrefix("/styles/", http.FileServer(http.Dir("reservation/styles"))))
	http.ListenAndServe(":8080", nil)
	if err := http.ListenAndServe(":8080", nil); err != nil {
		log.Fatalf("Server failed: %v", err)
	}
}

func mainForm(w http.ResponseWriter, r *http.Request) {
	fmt.Println("Received request for main page")
	http.ServeFile(w, r, "index.html")
}

func parkenForm(w http.ResponseWriter, r *http.Request) {
	fmt.Println("Received request for parken page")
	http.ServeFile(w, r, "parken.html")
}

func contactForm(w http.ResponseWriter, r *http.Request) {
	fmt.Println("Received request for contact page")
	accommodations := []Accommodation{
		{Name: "Chalet", Des: "hoe hoe", Price: "€100 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Belgie"},
		{Name: "Villa", Des: "hahaha", Price: "€200 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Duitsland"},
		{Name: "Appartement", Des: "hihihihi", Price: "€150 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Nederland"},
		{Name: "Strandhuis", Des: "Aan het strand", Price: "€250 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Duitsland"},
		{Name: "Berghut", Des: "In de bergen", Price: "€180 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Nederland"},
		{Name: "Chalet", Des: "hoe hoe", Price: "€100 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Belgie"},
		{Name: "Villa", Des: "hahaha", Price: "€200 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Duitsland"},
		{Name: "Appartement", Des: "hihihihi", Price: "€150 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Nederland"},
		{Name: "Strandhuis", Des: "Aan het strand", Price: "€250 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Duitsland"},
		{Name: "Berghut", Des: "In de bergen", Price: "€180 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Nederland"},
		{Name: "Chalet", Des: "hoe hoe", Price: "€100 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Belgie"},
		{Name: "Villa", Des: "hahaha", Price: "€200 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Duitsland"},
		{Name: "Appartement", Des: "hihihihi", Price: "€150 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Nederland"},
		{Name: "Strandhuis", Des: "Aan het strand", Price: "€250 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Duitsland"},
		{Name: "Berghut", Des: "In de bergen", Price: "€180 per nacht", ImageURL: "images/vakantiehuisje.png", Location: "Nederland"},
	}
	tmpl := template.Must(template.ParseFiles("contact.html"))
	err := tmpl.Execute(w, accommodations)
	if err != nil {
		http.Error(w, "Error rendering template", http.StatusInternalServerError)
		return
	}
}

func serveForm(w http.ResponseWriter, r *http.Request) {
	fmt.Println("Received request for serve page")
	http.ServeFile(w, r, "reservation/login.html")
}

func serveRegisterForm(w http.ResponseWriter, r *http.Request) {
	fmt.Println("Received request for regestratie page")
	http.ServeFile(w, r, "reservation/register.html")
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

	firstName := r.Form.Get("firstname")
	lastName := r.Form.Get("lastname")
	email := r.Form.Get("email")
	licensePlate := r.Form.Get("license_plate")

	formData := FormData{
		FirstName:    firstName,
		LastName:     lastName,
		Email:        email,
		LicensePlate: licensePlate,
	}

	tmpl := template.Must(template.ParseFiles("reservation/submit.html"))
	err = tmpl.Execute(w, formData)
	if err != nil {
		http.Error(w, "Error rendering template", http.StatusInternalServerError)
		return
	}
}
