import { Form, Head, usePage } from '@inertiajs/react';
import PersonalizationController from '@/actions/App/Http/Controllers/Settings/PersonalizationController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { edit } from '@/routes/personalization';

export default function Personalization() {
    const { auth } = usePage().props;

    return (
        <>
            <Head title="Personalization" />

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title="Personalization"
                    description="Tell Swali about you. It reads this before every answer."
                />

                <Form
                    {...PersonalizationController.update.form()}
                    options={{ preserveScroll: true }}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="instructions">
                                    Custom instructions
                                </Label>
                                <textarea
                                    id="instructions"
                                    name="instructions"
                                    rows={6}
                                    defaultValue={auth.user.instructions ?? ''}
                                    placeholder="I am a Laravel developer in Arusha. Keep answers short."
                                    className="rounded-md border bg-transparent px-3 py-2 text-sm"
                                />
                                <InputError message={errors.instructions} />
                            </div>

                            <Button disabled={processing}>Save</Button>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}

Personalization.layout = {
    breadcrumbs: [{ title: 'Personalization', href: edit() }],
};
