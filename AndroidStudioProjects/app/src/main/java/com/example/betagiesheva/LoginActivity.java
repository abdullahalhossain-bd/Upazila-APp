package com.example.betagiesheva;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;

import com.airbnb.lottie.LottieAnimationView;
import com.android.volley.Request;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.material.textfield.TextInputEditText;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class LoginActivity extends AppCompatActivity {

    private TextInputEditText etPhone, etPassword;
    private CardView btnLogin;
    private TextView registerShift;
    private LottieAnimationView progress;

    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        sessionManager = new SessionManager(this);

        // যদি আগেই login থাকে
        if (sessionManager.isLogin()) {
            startActivity(new Intent(this, HomeActivity.class));
            finish();
        }

        etPhone = findViewById(R.id.etPhoneNumber);
        etPassword = findViewById(R.id.etPassword);
        btnLogin = findViewById(R.id.btnCreateAccount);
        registerShift = findViewById(R.id.registerActivityShift);
        progress = findViewById(R.id.progress);

        btnLogin.setOnClickListener(v -> loginUser());

        registerShift.setOnClickListener(v -> {
            startActivity(new Intent(this, RegistrationActivity.class));
            finish();
        });
    }

    private void loginUser() {
        String phone = etPhone.getText().toString().trim();
        String password = etPassword.getText().toString().trim();

        if (phone.isEmpty() || password.isEmpty()) {
            Toast.makeText(this, "ফোন ও পাসওয়ার্ড দিন", Toast.LENGTH_SHORT).show();
            return;
        }

        progress.setVisibility(View.VISIBLE);

        StringRequest request = new StringRequest(Request.Method.POST, Config.LOGIN,
                response -> {
                    progress.setVisibility(android.view.View.GONE);
                    try {
                        JSONObject json = new JSONObject(response);

                        if (json.getBoolean("success")) {
                            JSONObject user = json.getJSONObject("user");
                            String token = json.optString("token", "");

                            // Save full user info including user_type AND JWT token.
                            // The token is REQUIRED for all add/update/delete API calls
                            // (the server's authUser() helper checks Authorization: Bearer <token>).
                            sessionManager.saveUser(
                                    user.optString("id", ""),
                                    user.optString("name", ""),
                                    user.optString("phone", ""),
                                    user.optString("address", ""),
                                    user.optString("union_name", ""),
                                    user.optString("image", "default.png"),
                                    user.optString("user_type", "user"),
                                    token
                            );

                            Toast.makeText(this, "লগইন সফল", Toast.LENGTH_SHORT).show();

                            // Redirect to HomeActivity
                            startActivity(new Intent(this, HomeActivity.class));
                            finish();

                        } else {
                            Toast.makeText(this,
                                    json.getString("message"),
                                    Toast.LENGTH_SHORT).show();
                        }

                    } catch (Exception e) {
                        e.printStackTrace();
                        Toast.makeText(this, "ডাটা প্রসেসিং সমস্যা", Toast.LENGTH_SHORT).show();
                    }
                },
                error -> {
                    progress.setVisibility(android.view.View.GONE);
                    Toast.makeText(this, "সার্ভার সংযোগ ব্যর্থ", Toast.LENGTH_SHORT).show();
                }) {

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> map = new HashMap<>();
                map.put("phone", phone);
                map.put("password", password);
                return map;
            }
        };

        Volley.newRequestQueue(this).add(request);
    }
}
