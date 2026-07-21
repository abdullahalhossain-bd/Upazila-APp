package com.example.betagiesheva.Fragment;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.betagiesheva.Adapter.RecyclerAdapter;
import com.example.betagiesheva.Model.Item;
import com.example.betagiesheva.R;
import com.example.betagiesheva.UpdateProfileActivity;

import java.util.ArrayList;
import java.util.List;

public class ServiceFragment extends Fragment {
    private RecyclerView recyclerView;
    private RecyclerAdapter adapter;
    private List<Item> itemList;

    public ServiceFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_service, container, false);

        recyclerView = view.findViewById(R.id.recycleView);
        recyclerView.setLayoutManager(new GridLayoutManager(getContext(), 3));

        // Optional shortcut: allow user to go to profile update from the top strip
        View profileUpdate = view.findViewById(R.id.profileupdate);
        if (profileUpdate != null) {
            profileUpdate.setOnClickListener(v -> {
                Intent intent = new Intent(getActivity(), UpdateProfileActivity.class);
                startActivity(intent);
            });
        }

        // Prepare the data
        itemList = new ArrayList<>();

        // Health Services Cards
        itemList.add(new Item("হাসপাতাল", R.drawable.ic_hospital));
        itemList.add(new Item("ক্লিনিক", R.drawable.ic_clinic));
        itemList.add(new Item("উপজেলা ডাক্তার", R.drawable.upaziladoctor));
        itemList.add(new Item("ডাক্তার", R.drawable.doctor));
        itemList.add(new Item("পশু ডাক্তার", R.drawable.veterinarian));
        itemList.add(new Item("নার্স সেবা", R.drawable.nurse));
        itemList.add(new Item("ফার্মেসি", R.drawable.pharmacy));
        itemList.add(new Item("অ্যাম্বুলেন্স", R.drawable.ambulance));
        itemList.add(new Item("ব্লাড ডোনার", R.drawable.blood_donar));

        // All Services Cards - First Row
        itemList.add(new Item("দর্শনীয় স্থান", R.drawable.tourist));
        itemList.add(new Item("হোটেল", R.drawable.hotel));
        itemList.add(new Item("পত্রিকা", R.drawable.newspaper));

        // All Services Cards - Second Row
        itemList.add(new Item("উপজেলা তথ্য", R.drawable.upazilainfo));
        itemList.add(new Item("নার্সারি দোকান", R.drawable.nursey_dokan));
        itemList.add(new Item("কুরিয়ার সার্ভিস", R.drawable.ic_courier));

        // All Services Cards - Third Row
        itemList.add(new Item("পোষ্ট অফিস", R.drawable.post));
        itemList.add(new Item("শিক্ষা প্রতিষ্ঠান", R.drawable.educational));
        itemList.add(new Item("ব্যাংক", R.drawable.bank));

        // All Services Cards - Fourth Row
        itemList.add(new Item("রেস্টুরেন্ট", R.drawable.resturent));
        itemList.add(new Item("বিউটি পার্লার", R.drawable.parlour));
        itemList.add(new Item("প্রশিক্ষণ কেন্দ্র", R.drawable.ic_traning));


        // All Services Cards - Sixth Row
        itemList.add(new Item("বিয়ের ঘটক", R.drawable.gatok));
        itemList.add(new Item("পল্লি বিদ্যুৎ", R.drawable.elec));
        itemList.add(new Item("সাংবাদিক", R.drawable.journalist));

        // All Services Cards - Seventh Row
        itemList.add(new Item("দোকান-শোরুম", R.drawable.shop));
        itemList.add(new Item("বাস", R.drawable.bus));
        itemList.add(new Item("অনলাইন সার্ভিস", R.drawable.onlineservice));

        // All Services Cards - Eighth Row
        itemList.add(new Item("কোচিং সেন্টার", R.drawable.tution));
        itemList.add(new Item("দলিল লেখক", R.drawable.ic_writer));
        itemList.add(new Item("সার্ভেয়ার", R.drawable.ic_surveyor));

        // History Cards
        itemList.add(new Item("জুলাই বিপ্লব", R.drawable.abusayed));
        itemList.add(new Item("সকল সামরিক অভ্যুত্থান", R.drawable.soldier));
        itemList.add(new Item("মহান মুক্তিযুদ্ধ", R.drawable.war));

        // Mistri Services Cards - First Row
        itemList.add(new Item("কাঠের মিস্ত্রি", R.drawable.carpenter));
        itemList.add(new Item("রাজমিস্ত্রি", R.drawable.brilding));
        itemList.add(new Item("রং মিস্ত্রি", R.drawable.ic_painter));

        // Mistri Services Cards - Second Row
        itemList.add(new Item("গাড়ি মেকার", R.drawable.car));
        itemList.add(new Item("ইলেক্ট্রনিকাল মেকার", R.drawable.mobilemaker));
        itemList.add(new Item("দর্জি কারিগর", R.drawable.tailor));

        // Important Services Cards - First Row
        itemList.add(new Item("জরুরি সেবা", R.drawable.emergency));
        itemList.add(new Item("জরুরী তথ্য", R.drawable.govtinfo));
        itemList.add(new Item("জরুরী নম্বার", R.drawable.emergencycall));

        // Important Services Cards - Second Row
        itemList.add(new Item("থানা পুলিশ", R.drawable.poli));
        itemList.add(new Item("ফায়ার সার্ভিস", R.drawable.fire));
        itemList.add(new Item("সকল সিমের কোড", R.drawable.sokolsim));

        // ================= NEW: 100 additional service cards =================
        // NOTE: The drawables below (R.drawable.ic_default_service) do not exist yet.
        // Add a matching PNG/vector icon in res/drawable for each ic_xxx name
        // below (or swap in an existing icon), otherwise the project won't compile.

        // ---- Government & Administration ----
        itemList.add(new Item("ইউনিয়ন পরিষদ কার্যালয়", R.drawable.ic_default_service));
        itemList.add(new Item("উপজেলা প্রশাসন কার্যালয়", R.drawable.ic_default_service));
        itemList.add(new Item("ভূমি অফিস", R.drawable.ic_default_service));
        itemList.add(new Item("সাব-রেজিস্ট্রি অফিস", R.drawable.ic_default_service));
        itemList.add(new Item("পাসপোর্ট সহায়তা কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("নির্বাচন অফিস", R.drawable.ic_default_service));
        itemList.add(new Item("সমাজসেবা অফিস", R.drawable.ic_default_service));
        itemList.add(new Item("কৃষি সম্প্রসারণ অফিস", R.drawable.ic_default_service));

        // ---- Legal Services ----
        itemList.add(new Item("আইনজীবী তালিকা", R.drawable.ic_default_service));
        itemList.add(new Item("আইনগত সহায়তা কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("নোটারি পাবলিক", R.drawable.ic_default_service));
        itemList.add(new Item("গ্রাম আদালত", R.drawable.ic_default_service));
        itemList.add(new Item("ভোক্তা অধিকার অফিস", R.drawable.ic_default_service));

        // ---- Healthcare ----
        itemList.add(new Item("ডেন্টাল ক্লিনিক", R.drawable.ic_default_service));
        itemList.add(new Item("চক্ষু হাসপাতাল", R.drawable.ic_default_service));
        itemList.add(new Item("ডায়াগনস্টিক সেন্টার", R.drawable.ic_default_service));
        itemList.add(new Item("হোমিও ও আয়ুর্বেদিক চিকিৎসক", R.drawable.ic_default_service));
        itemList.add(new Item("মানসিক স্বাস্থ্য সেবা কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("ফিজিওথেরাপি সেন্টার", R.drawable.ic_default_service));
        itemList.add(new Item("মা ও শিশু স্বাস্থ্য কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("টিকাদান কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("নার্সিং হোম", R.drawable.ic_default_service));

        // ---- Education ----
        itemList.add(new Item("কলেজ", R.drawable.ic_default_service));
        itemList.add(new Item("মাদ্রাসা", R.drawable.ic_default_service));
        itemList.add(new Item("পাবলিক লাইব্রেরি", R.drawable.ic_default_service));
        itemList.add(new Item("কম্পিউটার প্রশিক্ষণ কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("ভাষা শিক্ষা কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("বৃত্তি তথ্য কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("প্রতিবন্ধী শিক্ষা প্রতিষ্ঠান", R.drawable.ic_default_service));

        // ---- Agriculture ----
        itemList.add(new Item("সার ডিলার", R.drawable.ic_default_service));
        itemList.add(new Item("বীজ ভাণ্ডার", R.drawable.ic_default_service));
        itemList.add(new Item("কীটনাশক দোকান", R.drawable.ic_default_service));
        itemList.add(new Item("কৃষি যন্ত্রপাতি ভাড়া ও বিক্রয়", R.drawable.ic_default_service));
        itemList.add(new Item("মৎস্য চাষ তথ্য কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("গবাদি পশুর হাট", R.drawable.ic_default_service));
        itemList.add(new Item("কৃষি ঋণ তথ্য কেন্দ্র", R.drawable.ic_default_service));

        // ---- Women & Family Welfare ----
        itemList.add(new Item("মহিলা বিষয়ক অফিস", R.drawable.ic_default_service));
        itemList.add(new Item("নারী নির্যাতন প্রতিরোধ হেল্পলাইন", R.drawable.ic_default_service));
        itemList.add(new Item("মাতৃত্বকালীন ভাতা কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("নারী উদ্যোক্তা কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("নারী আশ্রয় কেন্দ্র", R.drawable.ic_default_service));

        // ---- Youth Development ----
        itemList.add(new Item("যুব ক্লাব", R.drawable.ic_default_service));
        itemList.add(new Item("ক্যারিয়ার কাউন্সেলিং সেন্টার", R.drawable.ic_default_service));
        itemList.add(new Item("স্বেচ্ছাসেবী সংগঠন", R.drawable.ic_default_service));
        itemList.add(new Item("উদ্যোক্তা উন্নয়ন কেন্দ্র", R.drawable.ic_default_service));

        // ---- Elderly Care ----
        itemList.add(new Item("বয়স্ক ভাতা কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("প্রবীণ নিবাস", R.drawable.ic_default_service));
        itemList.add(new Item("প্রবীণ স্বাস্থ্যসেবা কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("প্রবীণ কল্যাণ সংগঠন", R.drawable.ic_default_service));

        // ---- Disability Services ----
        itemList.add(new Item("প্রতিবন্ধী ভাতা কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("প্রতিবন্ধী পুনর্বাসন কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("হুইলচেয়ার ও সহায়ক উপকরণ সরবরাহ", R.drawable.ic_default_service));
        itemList.add(new Item("সংকেত ভাষা দোভাষী", R.drawable.ic_default_service));

        // ---- Transportation ----
        itemList.add(new Item("সিএনজি অটোরিকশা স্ট্যান্ড", R.drawable.ic_default_service));
        itemList.add(new Item("রেলওয়ে স্টেশন তথ্য", R.drawable.ic_default_service));
        itemList.add(new Item("লঞ্চ নৌ ঘাট", R.drawable.ic_default_service));
        itemList.add(new Item("ট্রাক পিকআপ ভাড়া সার্ভিস", R.drawable.ic_default_service));
        itemList.add(new Item("গ্যারেজ সার্ভিসিং সেন্টার", R.drawable.ic_default_service));

        // ---- Religious Services ----
        itemList.add(new Item("মসজিদ তালিকা", R.drawable.ic_default_service));
        itemList.add(new Item("মন্দির তালিকা", R.drawable.ic_default_service));
        itemList.add(new Item("গির্জা তালিকা", R.drawable.ic_default_service));
        itemList.add(new Item("ধর্মীয় অনুষ্ঠান সূচি", R.drawable.ic_default_service));

        // ---- Community & NGO ----
        itemList.add(new Item("এনজিও তালিকা", R.drawable.ic_default_service));
        itemList.add(new Item("সমবায় সমিতি", R.drawable.ic_default_service));
        itemList.add(new Item("এতিমখানা", R.drawable.ic_default_service));
        itemList.add(new Item("রক্তদান সংগঠন", R.drawable.ic_default_service));

        // ---- Environment & Disaster Management ----
        itemList.add(new Item("বর্জ্য ব্যবস্থাপনা কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("বৃক্ষরোপণ কর্মসূচি তথ্য", R.drawable.ic_default_service));
        itemList.add(new Item("পানি শোধনাগার সরবরাহ অফিস", R.drawable.ic_default_service));
        itemList.add(new Item("বন্যা দুর্যোগ ব্যবস্থাপনা কেন্দ্র", R.drawable.ic_default_service));

        // ---- Sports & Recreation ----
        itemList.add(new Item("খেলার মাঠ", R.drawable.ic_default_service));
        itemList.add(new Item("সাঁতার প্রশিক্ষণ কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("স্পোর্টস ক্লাব", R.drawable.ic_default_service));

        // ---- Tourism & Leisure ----
        itemList.add(new Item("পিকনিক স্পট", R.drawable.ic_default_service));
        itemList.add(new Item("রিসোর্ট", R.drawable.ic_default_service));
        itemList.add(new Item("ঐতিহাসিক স্থান তথ্য", R.drawable.ic_default_service));

        // ---- Employment & Livelihood ----
        itemList.add(new Item("চাকরির তথ্য কেন্দ্র", R.drawable.ic_default_service));
        itemList.add(new Item("বিদেশ গমন সহায়তা কেন্দ্র", R.drawable.ic_default_service));

        // ---- Home & Daily Life Services ----
        itemList.add(new Item("হোম রিপেয়ার সার্ভিস", R.drawable.ic_default_service));
        itemList.add(new Item("গৃহকর্মী সার্ভিস", R.drawable.ic_default_service));
        itemList.add(new Item("হোম ডেলিভারি সার্ভিস", R.drawable.ic_default_service));
        itemList.add(new Item("গ্যাস সিলিন্ডার সরবরাহকারী", R.drawable.ic_default_service));
        itemList.add(new Item("বিদ্যুৎ অফিস", R.drawable.ic_default_service));
        itemList.add(new Item("পানির ট্যাংক সাপ্লাই সার্ভিস", R.drawable.ic_default_service));
        itemList.add(new Item("পোকামাকড় নিয়ন্ত্রণ সার্ভিস", R.drawable.ic_default_service));
        itemList.add(new Item("হাউস পেইন্টিং সার্ভিস", R.drawable.ic_default_service));

        // ---- Local Economy & Business ----
        itemList.add(new Item("মুদি দোকান", R.drawable.ic_default_service));
        itemList.add(new Item("কাঁচা বাজার", R.drawable.ic_default_service));
        itemList.add(new Item("ইলেকট্রনিক্স ও মোবাইল দোকান", R.drawable.ic_default_service));
        itemList.add(new Item("প্রিন্টিং ও ফটোকপি দোকান", R.drawable.ic_default_service));
        itemList.add(new Item("মোবাইল ব্যাংকিং এজেন্ট", R.drawable.ic_default_service));
        itemList.add(new Item("এটিএম বুথ তালিকা", R.drawable.ic_default_service));
        itemList.add(new Item("ভাড়া বাসা জমি তথ্য", R.drawable.ic_default_service));
        itemList.add(new Item("টেইলারিং মেশিন সরঞ্জাম বিক্রেতা", R.drawable.ic_default_service));

        // ---- Public Facilities & Culture ----
        itemList.add(new Item("কমিউনিটি সেন্টার", R.drawable.ic_default_service));
        itemList.add(new Item("পাবলিক টয়লেট", R.drawable.ic_default_service));
        itemList.add(new Item("সরকারি রেস্ট হাউস", R.drawable.ic_default_service));
        itemList.add(new Item("সাংস্কৃতিক সংগঠন", R.drawable.ic_default_service));
        itemList.add(new Item("পার্ক উদ্যান", R.drawable.ic_default_service));
        itemList.add(new Item("সিটিজেন চার্টার ফরম ডাউনলোড কেন্দ্র", R.drawable.ic_default_service));

        // Set adapter
        adapter = new RecyclerAdapter(getContext(), itemList);
        recyclerView.setAdapter(adapter);

        return view;
    }
}