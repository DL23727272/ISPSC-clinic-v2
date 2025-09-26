 document.addEventListener("DOMContentLoaded", function () {
        // Get student_id from sessionStorage
        const studentId = sessionStorage.getItem("student_id");
        if (studentId) {
            document.getElementById("student_id").value = studentId;
        }

        const form = document.getElementById("certForm");

        form.addEventListener("submit", function (e) {
            e.preventDefault(); // stop normal submission

            // Show SweetAlert spinner while sending
            Swal.fire({
                title: "Submitting...",
                text: "Please wait while we send your certificate.",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Collect form data
            const formData = new FormData(form);

            // Send via AJAX
           fetch("./backend/certificate_submit.php", { method: "POST", body: formData })
            .then(res => res.json())
            .then(data => {
                Swal.close();
                Swal.fire({
                    icon: data.status,
                    title: data.status === "success" ? "Submitted!" : "Error",
                    text: data.message
                }).then(() => {
                    if (data.status === "success") {
                        form.reset(); // clear form after submit
                    }
                });
            })
            .catch(err => {
                Swal.close();
                Swal.fire({ icon: "error", title: "Error", text: "Something went wrong." });
            });


        });
    });