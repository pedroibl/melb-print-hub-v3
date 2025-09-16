import Layout from '@/Components/Layout';

export default function Dashboard({ csrf_token }) {
    return (
        <Layout title="Dashboard" csrf_token={csrf_token}>
            <section className="py-10">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold text-gray-900 mb-6">Dashboard</h1>
                    <div className="bg-white rounded-lg shadow p-6">
                        <p className="text-gray-700">
                            Welcome to your dashboard. Use the navigation to access admin features.
                        </p>
                    </div>
                </div>
            </section>
        </Layout>
    );
}


